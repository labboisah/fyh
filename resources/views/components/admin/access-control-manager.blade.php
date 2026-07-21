<div>
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-shield-check me-2 text-success"></i>
                Access Control
            </h1>
            <p class="text-muted mb-0">Create custom roles, define permissions, and assign users.</p>
        </div>

        <button type="button" class="btn btn-success" wire:click="createRole">
            <i class="bi bi-plus-circle me-1"></i>
            New Role
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <p class="text-muted small mb-1">Roles</p>
                <h4 class="mb-0">{{ number_format($summary['roles']) }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <p class="text-muted small mb-1">Permissions</p>
                <h4 class="mb-0">{{ number_format($summary['permissions']) }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <p class="text-muted small mb-1">Users</p>
                <h4 class="mb-0">{{ number_format($summary['users']) }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3 h-100">
                <p class="text-muted small mb-1">Temp Permissions</p>
                <h4 class="mb-0">{{ number_format($summary['temporary_permissions']) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Roles</h5>
                </div>
                <div class="card-body">
                    <input type="search" class="form-control mb-3" placeholder="Search roles" wire:model.live.debounce.300ms="roleSearch">

                    <div class="list-group" style="max-height: 620px; overflow-y: auto;">
                        @forelse($roles as $role)
                            <button type="button" wire:click="selectRole({{ $role->id }})" class="list-group-item list-group-item-action {{ $selectedRole?->id === $role->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold">{{ $role->display_name ?: ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                                        <small>{{ $role->name }}</small>
                                    </div>
                                    <span class="badge {{ $selectedRole?->id === $role->id ? 'text-bg-light' : 'text-bg-secondary' }}">{{ $role->permissions_count }}</span>
                                </div>
                            </button>
                        @empty
                            <div class="text-center text-muted py-4">No roles found.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Role Builder</h5>
                    @if($selectedRole?->name === 'administrator')
                        <span class="badge text-bg-warning">Protected</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role Name</label>
                            <input type="text" class="form-control @error('roleName') is-invalid @enderror" wire:model.defer="roleName" placeholder="billing_supervisor" @disabled($selectedRole?->name === 'administrator')>
                            @error('roleName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control @error('roleDisplayName') is-invalid @enderror" wire:model.defer="roleDisplayName" placeholder="Billing Supervisor" @disabled($selectedRole?->name === 'administrator')>
                            @error('roleDisplayName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('roleDescription') is-invalid @enderror" rows="2" wire:model.defer="roleDescription" @disabled($selectedRole?->name === 'administrator')></textarea>
                            @error('roleDescription')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Permissions</h6>
                        <div class="d-flex gap-2">
                            <input type="search" class="form-control form-control-sm" placeholder="Search permissions" wire:model.live.debounce.300ms="permissionSearch">
                            <select class="form-select form-select-sm" wire:model.live="moduleFilter">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}">{{ ucfirst(str_replace('_', ' ', $module)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="max-height: 520px; overflow-y: auto;">
                        @forelse($permissionGroups as $module => $permissions)
                            <div class="border rounded mb-3">
                                <div class="bg-light px-3 py-2 d-flex justify-content-between align-items-center">
                                    <strong>{{ ucfirst(str_replace(['_', '-'], ' ', $module)) }}</strong>
                                    @if($selectedRole?->name !== 'administrator')
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-success" wire:click="selectModulePermissions('{{ $module }}')">Select</button>
                                            <button type="button" class="btn btn-outline-secondary" wire:click="clearModulePermissions('{{ $module }}')">Clear</button>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <div class="row g-2">
                                        @foreach($permissions as $permission)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" id="permission{{ $permission->id }}" wire:model.live="selectedPermissionIds" @disabled($selectedRole?->name === 'administrator')>
                                                    <label class="form-check-label" for="permission{{ $permission->id }}">
                                                        <span class="fw-semibold">{{ $permission->display_name ?: $permission->name }}</span>
                                                        <span class="d-block text-muted small">{{ $permission->name }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">No permissions found.</div>
                        @endforelse
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-success" wire:click="saveRole" @disabled($selectedRole?->name === 'administrator')>
                            <i class="bi bi-check-circle me-1"></i>
                            Save Role
                        </button>
                        @if($selectedRole && $selectedRole->name !== 'administrator')
                            <button type="button" class="btn btn-outline-danger" wire:click="deleteRole" wire:confirm="Delete this role?">
                                <i class="bi bi-trash me-1"></i>
                                Delete Role
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Create Permission</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('permissionName') is-invalid @enderror" wire:model.defer="permissionName" placeholder="billing.approve">
                        @error('permissionName')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Name</label>
                        <input type="text" class="form-control" wire:model.defer="permissionDisplayName" placeholder="Approve Billing">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Module</label>
                        <input type="text" class="form-control" wire:model.defer="permissionModule" placeholder="billing">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="2" wire:model.defer="permissionDescription"></textarea>
                    </div>
                    <button type="button" class="btn btn-outline-success w-100" wire:click="savePermission">
                        <i class="bi bi-plus-circle me-1"></i>
                        Add Permission
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Users In Role</h5>
                </div>
                <div class="card-body">
                    @if($selectedRole)
                        <div style="max-height: 360px; overflow-y: auto;">
                            @foreach($users as $user)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="{{ $user->id }}" id="roleUser{{ $user->id }}" wire:model.live="selectedUserIds" @disabled($selectedRole->name === 'administrator')>
                                    <label class="form-check-label" for="roleUser{{ $user->id }}">
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                        <span class="d-block text-muted small">{{ $user->email }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary w-100 mt-3" wire:click="syncRoleUsers" @disabled($selectedRole->name === 'administrator')>
                            <i class="bi bi-people me-1"></i>
                            Update Users
                        </button>
                    @else
                        <div class="text-muted text-center py-4">Save or select a role first.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
