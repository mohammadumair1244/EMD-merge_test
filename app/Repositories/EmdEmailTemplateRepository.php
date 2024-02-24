<?php
namespace App\Repositories;

use App\Interfaces\EmdEmailTemplateInterface;
use App\Models\EmdEmailTemplate;
use Illuminate\Database\Eloquent\Collection;

class EmdEmailTemplateRepository implements EmdEmailTemplateInterface
{
    public function __construct(protected EmdEmailTemplate $emd_email_template_model)
    {
    }

    public function view_page(): EmdEmailTemplate | Collection
    {
        return $this->emd_email_template_model->get();
    }
    public function create_page($request): bool
    {
        $this->emd_email_template_model->create($request->except(['_token']));
        return true;
    }
}
