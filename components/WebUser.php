<?php

/**
 * The class is instance of CWebUser
 */
class WebUser extends CWebUser {
    
    /**
     * Use helper trait
     */
    use WebUserTrait;

    protected function beforeLogin($id, $states, $fromCookie) {
        $user = User::model()->findByPk($id);

        if ($user instanceof User && $user->isBanned())
            return false;
        else
            return true;
    }

    protected function afterLogin($fromCookie) {
        $user = User::model()->findByPk($this->id);
        if ($user instanceof User)
            $user->updateLastLoginDate();
    }
    
    protected function afterLogout() {
        parent::afterLogout();
        
        $this->flushDashboardBtnUrl();
    }

    /**
     * Set auto show login popup
     * 
     * @author Duong <duong.n@scopicsoftware.com>
     * @return void
     */
    public function loginRequired(){
        if(!Yii::app()->getRequest()->getIsAjaxRequest()){
            Yii::app()->session['autoLoginForm'] = true;
        }

        parent::loginRequired();
    }
    
    /**
     * Store the dashboard btn url to cookies.
     * @param string $url
     * @return void
     */
    public function setDashboardBtnUrl($url) {
//         $cookie = new CHttpCookie('dashboardBtnUrl', $url);
//         $cookie->expire = time() + 3600 * 24 * 30; // 30 days
//         Yii::app()->request->cookies['dashboardBtnUrl'] = $cookie;
		//URL property within cookie could be changed by xss or other attack, so let's store it within session.
        Yii::app()->session['dashboardBtnUrl'] = $url;
    }
    
    /**
     * The function will return the dashboard
     * button url from cookies as the user state
     * is being deleted at the end of session.
     *
     * @return mixed String URL or false if not exists
     */
    public function getDashboardBtnUrl() {
        if(Yii::app()->session->contains('dashboardBtnUrl')){
            return Yii::app()->session['dashboardBtnUrl'];
        }

        //regenerate
        $this->regenerateDashboardUrl();

        if(Yii::app()->session->contains('dashboardBtnUrl')){
            return Yii::app()->session['dashboardBtnUrl'];
        }

        return false;
    }
    
    /**
     * Delete the dashboard btn url
     *
     * @return void
     */
    public function flushDashboardBtnUrl() {
        if( Yii::app()->session->contains('dashboardBtnUrl'))
            unset(Yii::app()->session['dashboardBtnUrl']);
    }

    /**
     * Get current user email
     *
     * @return string
     */
    public function getEmail() {
        $user = User::model()->findByPk($this->id);
        return $user instanceof User ? $user->email : '';
    }


    public function regenerateDashboardUrl(){
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($user instanceof User){
            switch ($user->status){
                case User::STATUS_ACTIVATED:

                    // Redirect to profile view if user does not have mother language specified
                    if (empty($user->mother_language_id))
                        $dashboardUrl = '/profile/view';
                    else
                    if ($user->isAllowedTakeTests()) {
                        $dashboardUrl = '/qualification/test/dashboard';
                    } else {
                         $dashboardUrl = '/register/failed';
                    }
                    break;

                case User::STATUS_APPROVED: 
                    $dashboardUrl = '/qualification/user/dashboard';
                    break;

                case User::STATUS_VERIFIED: 
                    $dashboardUrl = '/register/contract';
                    break;
            }

            Yii::app()->user->setDashboardBtnUrl($dashboardUrl);
        }
    }
}