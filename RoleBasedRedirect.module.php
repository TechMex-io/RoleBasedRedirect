<?php
namespace ProcessWire;
class RoleBasedRedirect extends WireData implements Module, ConfigurableModule {

    public static function getModuleInfo() {
        return array(
            'title' => 'Role-Based Redirect',
            'version' => '1.0.0',
            'summary' => 'Redirects users to specific URLs based on their roles upon login.',
            'author' => 'AlexRdz - tech-mex.io - alexrdz.me',
            'singular' => true,
            'autoload' => true,
        );
    }

    public function init() {
        $this->addHookAfter('Session::loginSuccess', $this, 'handleLoginRedirect');
    }

    protected function handleLoginRedirect(HookEvent $event) {
        $user = $event->arguments(0);
        $redirectUrl = $this->getRoleRedirectUrl($user);
        // $this->wire('log')->save('redirect', "2 Redirect URL: " . print_r($redirectUrl, true));

        if ($redirectUrl) {
            $this->wire('session')->redirect($redirectUrl);
            return;
        }
    }

    protected function getRoleRedirectUrl($user) {
        $redirects = $this->getRedirectConfig();

        foreach ($user->roles as $role) {
            if (isset($redirects[$role->name])) {
                return $redirects[$role->name];
            }
        }

        return null;
    }

    protected function getRedirectConfig() {
        $config = $this->redirectConfig;
        $redirects = array();

        foreach (explode("\n", $config) as $line) {
            $parts = explode('=', trim($line), 2);
            if (count($parts) == 2) {
                $redirects[trim($parts[0])] = trim($parts[1]);
            }
        }

        return $redirects;
    }

    public static function getModuleConfigInputfields(array $data) {
        $inputfields = new InputfieldWrapper();

        $field = wire('modules')->get('InputfieldTextarea');
        $field->attr('name', 'redirectConfig');
        $field->label = 'Role Redirect Configuration';
        $field->description = 'Enter one role=url pair per line. For example: admin=/site-admin';
        $field->value = isset($data['redirectConfig']) ? $data['redirectConfig'] : '';

        $inputfields->add($field);

        return $inputfields;
    }
}
