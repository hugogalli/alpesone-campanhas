<?php

namespace Alpes\Campaigns;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Campanhas',
            'description' => 'Plugin para criar e exibir campanhas com seções.',
            'author'      => 'Hugo Galli',
            'icon'        => 'icon-bullhorn'
        ];
    }

    public function registerComponents()
    {
        return [
            'Alpes\Campaigns\Components\CampaignPage' => 'campaignPage',
        ];
    }

    public function registerNavigation()
    {
        return [
            'campaigns' => [
                'label'       => 'Campanhas',
                'url'         => \Backend::url('alpes/campaigns/campaigns'),
                'icon'        => 'icon-bullhorn',
                'order'       => 500,
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'alpes.campaigns.manage' => [
                'tab' => 'Campanhas',
                'label' => 'Gerenciar campanhas'
            ],
        ];
    }
}
