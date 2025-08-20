<?php namespace Alpes\Campaigns\Components;

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
        $this->campaign = Campaign::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$this->campaign) {
            return $this->controller->run('404');
        }

        // Disponibiliza para o Twig
        $this->page['campaign'] = $this->campaign;

        // SEO
        if ($this->campaign->meta_title) {
            $this->page->title = $this->campaign->meta_title;
        }
        if ($this->campaign->meta_description) {
            $this->page->meta_description = $this->campaign->meta_description;
        }
    }
}
