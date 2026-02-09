<?php

namespace App\Http\Controllers\Web\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdatePasswordUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\Dto\DestroyUserDto;
use App\Services\User\Dto\StoreUserDto;
use App\Services\User\Dto\UpdatePasswordUserDto;
use App\Services\User\Dto\UpdateUserDto;
use App\Services\User\Http\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->userService->index();

        return view('AdminPanel.User.index', compact('users'));
    }

    /**
     * @param User $user
     * @return Factory|View|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('AdminPanel.User.show', compact('user'));
    }

    /**
     * @param User $user
     * @return Factory|View|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('AdminPanel.User.edit', compact('user'));
    }

    /**
     * @param User $user
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $data = $request->validated();

        $dto = new UpdateUserDto([
            ...$data,
            'user' => $user,
        ]);

        $this->userService->update($dto);

        return redirect()->route('admin.users.index');
    }

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function create()
    {
        return view('AdminPanel.User.create');
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $dto = new StoreUserDto($data);
        $this->userService->store($dto);

        return redirect()->route('admin.users.index');
    }

    /**
     * @param User $user
     * @return Factory|View|\Illuminate\View\View
     */
    public function resetPassword(User $user)
    {
        return view('AdminPanel.User.resetPassword', compact('user'));
    }

    /**
     * @param User $user
     * @param UpdatePasswordUserRequest $request
     * @return RedirectResponse
     */
    public function updatePassword(User $user, UpdatePasswordUserRequest $request)
    {
        $data = $request->validated();

        $dto = new UpdatePasswordUserDto([
            ...$data,
            'user' => $user,
        ]);
        $this->userService->updatePassword($dto);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $dto = new DestroyUserDto(['user' => $user]);
        $this->userService->destroy($dto);

        return redirect()->route('admin.users.index');
    }
}
