<?php
import('lib.pkp.classes.plugins.GenericPlugin');
class testesOJSPlugin extends GenericPlugin {
	public function register($category, $path, $mainContextId = NULL) {

    // Registre o plug-in mesmo quando não estiver ativado
    $success = parent::register($category, $path);

		if ($success && $this->getEnabled()) {
      // Faça algo quando o plug-in estiver ativado
    }

		return $success;
	}

  /**
   * Nome e descrição do plugin
   */
	public function getDisplayName() {
		return 'Teste para OJS';
	}

	public function getDescription() {
		return 'Este plugin é teste para construção de plugin OJS';
	}

    public function getActions($request, $actionArgs) {
    // Obtenha as ações existentes
        $actions = parent::getActions($request, $actionArgs);
        if (!$this->getEnabled()) {
            return $actions;
        }

    // Crie uma LinkAction que irá chamar 
    // o método `manage` do plugin com o verbo `settings`.
        $router = $request->getRouter();
        import('lib.pkp.classes.linkAction.request.AjaxModal');
        $linkAction = new LinkAction(
            'settings',
            new AjaxModal(
                $router->url(
                    $request,
                    null,
                    null,
                    'manage',
                    null,
                    array(
                        'verb' => 'settings',
                        'plugin' => $this->getName(),
                        'category' => 'generic'
                    )
                ),
                $this->getDisplayName()
            ),
            __('manager.plugins.settings'),
            null
        );

    // Adicione o LinkAction às ações existentes. 
    // Torne-a a primeira ação a ser consistente com outros plugins.
        array_unshift($actions, $linkAction);

        return $actions;
    }

    public function manage($args, $request) {
		switch ($request->getUserVar('verb')) {

      // Retorne uma resposta JSON contendo o formulário de configurações
      case 'settings':
        $templateMgr = TemplateManager::getManager($request);
        $settingsForm = $templateMgr->fetch($this->getTemplateResource('settings.tpl'));
        return new JSONMessage(true, $settingsForm);
		}
		return parent::manage($args, $request);
	}
}