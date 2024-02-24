<?php
namespace App\Repositories;

use App\Interfaces\EmdEmailListInterface;
use App\Models\EmdEmailList;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmdEmailListRepository implements EmdEmailListInterface
{
    public function __construct(protected EmdEmailList $emd_email_list_model)
    {
    }

    public function emd_email_list_page(): LengthAwarePaginator
    {
        return $this->emd_email_list_model->paginate(100);
    }
    public function emd_email_list_create($request): bool
    {
        if ($request->hasFile('email_csv')) {
            $path = $request->file('email_csv')->getRealPath();
            $data = array_map('str_getcsv', file($path));
            unset($data[0]);
            foreach ($data as $row) {
                $set_data['title'] = $request['title'];
                $set_data['email'] = $row[0];
                $this->emd_email_list_model->updateOrCreate(
                    ['email' => $set_data['email']],
                    $set_data
                );
            }
            return true;
        }
        return false;
    }
    public function emd_email_list_delete($id): bool
    {
        $this->emd_email_list_model->destroy($id);
        return true;
    }
}
