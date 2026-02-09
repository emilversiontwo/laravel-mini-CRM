<?php

namespace App\Services\Ticket\Http;

use App\Enums\Ticket\TicketStatusEnum;
use App\Models\Ticket;
use App\Services\Ticket\Dto\IndexTicketDto;
use App\Services\Ticket\Dto\StoreTicketDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TicketService
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function store(StoreTicketDto $dto): Ticket
    {
        $ticket = new Ticket();

        $ticket->subject = $dto->subject;
        $ticket->text = $dto->text;
        $ticket->status = TicketStatusEnum::NEW->value;

        $ticket->customer()->associate($dto->customer);

        if ($dto->files){
            /** @var UploadedFile $file */
            foreach ($dto->files as $file) {
                $ticket->addMedia($file)->setFileName(Hash::make(Carbon::now()->toDateTimeString()) . '.' . $file->getClientOriginalExtension())->toMediaCollection();
            }
        }

        $ticket->save();

        return $ticket;
    }

    public function index(IndexTicketDto $dto): LengthAwarePaginator
    {
        $tickets = Ticket::query();

        $tickets->when($dto->from, function (Builder $query) use ($dto) {
            $query->whereDate('created_at', '>=', $dto->from);
        });

        $tickets->when($dto->to, function (Builder $query) use ($dto) {
            $query->whereDate('created_at', '<=', $dto->to);
        });

        $tickets->when($dto->subject, function (Builder $query) use ($dto) {
            $query->where('subject', 'like', '%' . $dto->subject . '%');
        });

        $tickets->when($dto->text, function (Builder $query) use ($dto) {
            $query->where('text', 'like', '%' . $dto->text . '%');
        });

        $tickets->when($dto->status, function (Builder $query) use ($dto) {
            $query->where('status', '=', $dto->status->getValue());
        });

        $tickets->when($dto->name, function (Builder $query) use ($dto) {
            $query->whereHas('customer', function (Builder $query) use ($dto) {
                $query->where('name', 'like', '%' . $dto->name . '%');
            });
        });

        $tickets->when($dto->email, function (Builder $query) use ($dto) {
            $query->whereHas('customer', function (Builder $query) use ($dto) {
                $query->where('email', 'like', '%' . $dto->email . '%');
            });
        });

        $tickets->when($dto->phone, function (Builder $query) use ($dto) {
            $query->whereHas('customer', function (Builder $query) use ($dto) {
                $query->where('phone', 'like', '%' . $dto->phone . '%');
            });
        });

        return $tickets->paginate();
    }
}
