<?php

namespace Alpes\Campaigns\Components;

use Cms\Classes\ComponentBase;
use Alpes\Campaigns\Models\Campaign;

class CampaignPage extends ComponentBase
{
    public $campaign;

    public function componentDetails()
    {
        return [
            'name'        => 'Página da Campanha',
            'description' => 'Exibe uma campanha pelo slug.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug',
                'description' => 'Slug da campanha. Por padrão pega da URL.',
                'type'        => 'string',
                'default'     => '{{ :slug }}',
            ],
        ];
    }

    public function onRun()
    {
        $slug = $this->property('slug');
        $this->page['campaign'] = \Alpes\Campaigns\Models\Campaign::where('slug', $slug)
            ->where('is_active', true)->first();

        if (!$this->page['campaign']) return $this->controller->run('404');

        $this->addCss('/plugins/alpes/campaigns/assets/css/campaign.css');
        $this->addJs('/plugins/alpes/campaigns/assets/js/campaign.js');
    }
}
