<?php

/**
 * Class FontSupportedLanguagesForm
 *
 * This class is used for the Font Supported Languages update form
 *
 * @author Sergey <sergey.s@scopicsoftware.com>
 */
class FontSupportedLanguagesForm extends CModel
{
    public $id;
    public $font;
    public $supportedLanguages = array();

    public static function model()
    {
        return new self();
    }

    public function rules()
    {
        return array(
            array('supportedLanguages', 'safe'),
            array('supportedLanguages', 'supportedLanguagesCheck'),
        );
    }

    public function attributeNames()
    {
        return array(
            'supportedLanguages' => 'Supported languages',
        );
    }

    public function supportedLanguagesCheck($attribute, $params)
    {
        if (!is_null($this->$attribute) && !is_array($this->$attribute))
            $this->addError($attribute, 'Should be array of fonts or empty');
    }
    
    /**
     * @param int $pk
     * @return FontSupportedLanguagesForm
     */
    public function findByPk($pk)
    {
        $font = Font::model()->findByPk($pk);
        if ($font instanceof Font) {
            $this->id = $pk;
            $this->font = $font;
            $this->supportedLanguages = CHtml::listData($font->supportedLanguages, 'id', 'id');
            return $this;
        } else
            return null;
    }

    public function prepareAttributes($attributes)
    {
        $this->supportedLanguages = isset($attributes['supportedLanguages']) && is_array($attributes['supportedLanguages']) ? $attributes['supportedLanguages'] : array();
    }

    public function save()
    {
        $ret = true;
        
        $prevLanguages = $this->font->supportedLanguages;
        $newLanguages = Language::model()->findAllByPk($this->supportedLanguages);
        foreach ($prevLanguages as $oldLang)
        {
            foreach ($newLanguages as $newLang)
                if ($oldLang->id == $newLang->id)
                    continue 2;
            $oldRecord = LanguageSupportedFonts::model()->findByAttributes(
                array('language_id' => $oldLang->id, 'font_id' => $this->id)
            );
            if ($oldRecord instanceof LanguageSupportedFonts) {
                $oldRecord->delete();
            } else {
                $allFontsExceptCurrent = Font::model()->findAll(array(
                    'condition' => 'id != :font_id',
                    'params' => array(':font_id' => $this->font->id)
                ));
                foreach ($allFontsExceptCurrent as $font) {
                    $lsf = new LanguageSupportedFonts();
                    $lsf->language_id = $oldLang->id;
                    $lsf->font_id = $font->id;
                    $lsf->save();
                }
            }
        }
        foreach ($newLanguages as $newLang)
        {
            foreach ($prevLanguages as $oldLang)
                if ($oldLang->id == $newLang->id)
                    continue 2;
            $rec = new LanguageSupportedFonts();
            $rec->font_id = $this->id;
            $rec->language_id = $newLang->id;
            if ($rec->validate()) {
                $rec->save();
                $unsupportedFontExists = Font::model()->exists(array(
                    'condition' => 'id not in (select font_id from language_supported_fonts where language_id = :language_id) and active',
                    'params' => array(':language_id' => $newLang->id)
                ));
                if (!$unsupportedFontExists) {
                    LanguageSupportedFonts::model()->deleteAllByAttributes(array(
                        'language_id' => $newLang->id
                    ));
                }
            } else
                $ret = $ret && false;
        }
        return $ret;
    }
}