<?php

/**
 * Class LanguageSupportedFontsForm
 *
 * This class is used for the Language Supported Fonts update form
 *
 * @author Sergey <sergey.s@scopicsoftware.com>
 */
class LanguageSupportedFontsForm extends CModel
{
    public $id;
    public $language;
    public $supportedFonts = array();
    public $name;
    public $lang_iso;

    public static function model()
    {
        return new self();
    }

    public function rules()
    {
        return array(
            array('supportedFonts, name, lang_iso', 'safe'),
            array('name, lang_iso', 'required'),
            array('supportedFonts', 'supportedFontsCheck'),
            array('name, lang_iso', 'uniqueLanguageCheck'),
            array('name', 'length', 'max' => 45),
            array('lang_iso', 'length', 'max' => 3),
        );
    }

    public function attributeNames()
    {
        return array(
            'supportedFonts' => 'Supported fonts',
            'name' => 'Name',
            'lang_iso' => 'Short ISO code'
        );
    }

    public function supportedFontsCheck($attribute, $params)
    {
        if (!is_null($this->$attribute) && !is_array($this->$attribute))
            $this->addError($attribute, 'Should be array of fonts or empty');
    }
    
    public function uniqueLanguageCheck($attribute, $params)
    {
        $prevLanguage = Language::model()->findByAttributes(array(
            $attribute => $this->$attribute
        ));
        if ($prevLanguage && $prevLanguage->id != $this->id) {
            $this->addError($attribute, 'Language ' . $this->getAttributeLabel($attribute) . ' should be unique');
        }
    }

    /**
     * @param int $pk
     * return LanguageSupportedFontsForm
     */
    public function findByPk($pk)
    {
        $language = Language::model()->findByPk($pk);
        if ($language instanceof Language) {
            $this->id = $pk;
            $this->language = $language;
            $this->name = $language->name;
            $this->lang_iso = $language->lang_iso;
            $this->supportedFonts = CHtml::listData($language->supportedFonts, 'id', 'id');
            return $this;
        } else
            return null;
    }

    public function prepareAttributes($attributes)
    {
        $this->supportedFonts = isset($attributes['supportedFonts']) && is_array($attributes['supportedFonts']) ? $attributes['supportedFonts'] : array();
        $this->name = $attributes['name'];
        $this->lang_iso = $attributes['lang_iso'];
        //$this->supportedFonts = Font::model()->findAllByPk($this->supportedFonts);
    }

    public function save()
    {
        $ret = true;
        
        if ($this->language == null) {
            $this->language = new Language();
        }
        $this->language->name = $this->name;
        $this->language->lang_iso = $this->lang_iso;
        $this->language->save();
        $this->id = $this->language->id;
        
        $prevFonts = $this->language->supportedFonts;
        $newFonts = Font::model()->findAllByPk($this->supportedFonts);
        foreach ($prevFonts as $oldFont)
        {
            foreach ($newFonts as $newFont)
                if ($oldFont->id == $newFont->id)
                    continue 2;
            LanguageSupportedFonts::model()->findByAttributes(
                array('language_id' => $this->id, 'font_id' => $oldFont->id)
            )->delete();
        }
        foreach ($newFonts as $newFont)
        {
            foreach ($prevFonts as $oldFont)
                if ($oldFont->id == $newFont->id)
                    continue 2;
            $rec = new LanguageSupportedFonts();
            $rec->language_id = $this->id;
            $rec->font_id = $newFont->id;
            if ($rec->validate())
                $rec->save();
            else
                $ret = $ret && false;
        }
        return $ret;
    }
}