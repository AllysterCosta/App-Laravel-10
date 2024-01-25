<?php

namespace App\Repositories;

use stdClass;
use App\DTO\CreateSupportDTO;
use App\DTO\UpdateSupportDTO;
use App\Models\Support;
use App\Repositories\SupportRepositoryInterface;



class SupportEloquentORM implements SupportRepositoryInterface
{
    public function __construct(
        protected Support $model
    ){}

    public function getAll(string $filter = null) : array
    { /* Aqui é coletado o model, aplicado o filtro para retornar o Array */
        return $this->model
                    ->where(function ($query) use ($filter){
                        if ($filter){
                            $query->where('subject' , $filter);
                            $query->orWhere('body', 'like', "%{$filter}%");
                        }
                    })
                    ->get()
                    ->toArray();
    }
    public function findOne(string $id) : stdClass|null
    {
        $support = $this->model->find($id);
        if (!$support){
            return null;
        }
        return (object) $support->toArray();
    }
    public function delete(string $id) : void
    {
        $this->model->findOrFail($id)->delete();
    }
    public function new(CreateSupportDTO $dto) : stdClass
    {
        $support = $this->model->create(
            (array) $dto);

        return (object) $support->toArray();
    }
    public function update(UpdateSupportDTO $dto) : stdClass | null
    {
        if(!$support = $this->model->find($dto->id)){
            return null;
        }
        $support->update(
            (array) $dto
        );

        return (object) $support->toArray();
    }
}