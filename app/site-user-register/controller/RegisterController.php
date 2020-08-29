<?php
/**
 * RegisterController
 * @package site-user-register
 * @version 0.0.1
 */

namespace SiteUserRegister\Controller;

use SiteUserRegister\Library\Meta;
use LibForm\Library\Form;
use LibUserAuthCookie\Authorizer\Cookie;
use LibUserMain\Model\User;

class RegisterController extends \Site\Controller
{
	public function createAction() {
        $next = $this->req->getQuery('next');
        if(!$next)
            $next = $this->router->to('siteHome');

        if($this->user->isLogin())
            return $this->res->redirect($next);

        $form = new Form('site.me.register');

        $params = [
            '_meta' => [
                'title' => 'Register'
            ],
            'form'  => $form,
            'meta' => Meta::single(),
            'errors' => []
        ];

        if(!($valid = $form->validate()) || !$form->csrfTest('noob')){
            $params['errors'] = $form->getErrors();
            $this->res->render('me/register', $params);
            return $this->res->send();
        }

        if(!$id = User::create((array)$valid))
        	deb(User::lastError());

        Cookie::setKeep(false);
        Cookie::loginById($id);

        $this->res->redirect($next);
    }
}