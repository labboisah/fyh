@section('title', 'Dashboard')

<div wire:poll.10s>
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-speedometer2 me-2 text-success"></i>
                Dashboard
            </h1>
            <p class="text-muted mb-0">Welcome back, {{ $user->name }}. Your dashboard updates automatically.</p>
        </div>

        <div class="text-muted small text-end">
            <div wire:ignore>
                <i class="bi bi-calendar-event me-1"></i>
                <span id="user-dashboard-local-date">{{ $lastUpdated->format('F j, Y') }}</span>
            </div>
            <div wire:ignore>
                <i class="bi bi-clock me-1"></i>
                <span id="user-dashboard-local-time">{{ $lastUpdated->format('h:i:s A') }}</span>
            </div>
            <div>
                <i class="bi bi-arrow-repeat me-1"></i>
                Data refreshed {{ $lastUpdated->format('h:i:s A') }}
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach($cards as $card)
            <div class="col-md-6 col-xl-3">
                <div class="border rounded bg-white p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <p class="text-muted small mb-1">{{ $card['label'] }}</p>
                            <h3 class="mb-0">{{ is_numeric($card['value']) ? number_format($card['value']) : $card['value'] }}</h3>
                            <small class="text-muted">{{ $card['description'] }}</small>
                        </div>
                        <i class="bi {{ $card['icon'] }} fs-2 {{ $card['iconClass'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quick Actions</h5>
                    <span class="badge text-bg-success">Live</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($quickActions as $action)
                            <div class="col-md-12">
                                <a href="{{ $action['route'] }}" class="btn {{ $action['class'] }} w-100 py-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <i class="bi {{ $action['icon'] }} me-3 fs-4"></i>
                                        <div>
                                            <div class="fw-bold">{{ $action['label'] }}</div>
                                            <small class="text-muted">{{ $action['description'] }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">My Recent Activity</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="max-height: 430px; overflow-y: auto;">
                        @forelse($recentActivities as $activity)
                            @php
                                $actionLabel = ucwords(str_replace(['.', '_'], [' ', ' '], $activity->action));
                                $modelLabel = $activity->model_type ? class_basename($activity->model_type) : null;
                            @endphp
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold">{{ $actionLabel }}</div>
                                        <div class="small text-muted">
                                            @if($modelLabel)
                                                {{ $modelLabel }}
                                                @if($activity->model_id)
                                                    #{{ $activity->model_id }}
                                                @endif
                                            @else
                                                System activity
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end text-muted small">
                                        {{ $activity->created_at?->format('M j, h:i A') }}
                                        <div>{{ $activity->created_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">No recent activity found for your account.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function updateUserDashboardLocalClock() {
            const dateElement = document.getElementById('user-dashboard-local-date');
            const timeElement = document.getElementById('user-dashboard-local-time');

            if (!dateElement || !timeElement) {
                return;
            }

            const now = new Date();

            dateElement.textContent = now.toLocaleDateString(undefined, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });

            timeElement.textContent = now.toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        }

        updateUserDashboardLocalClock();
        setInterval(updateUserDashboardLocalClock, 1000);
    </script>
@endpush
