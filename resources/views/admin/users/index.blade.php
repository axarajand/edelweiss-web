<x-layouts.app title="Manajemen User - Edelweiss Detection">
    <x-slot:header>Manajemen User</x-slot:header>

    <div class="space-y-6">

        {{-- Status filter tabs --}}
        <div class="flex flex-wrap gap-2">
            @foreach ([
                'all' => 'Semua',
                'pending' => 'Pending',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
            ] as $key => $label)
                <a href="{{ route('admin.users.index', ['status' => $key]) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition
                          {{ $status === $key
                              ? 'bg-emerald-600 text-white'
                              : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ $label }}
                    <span class="px-1.5 py-0.5 rounded-md text-xs
                                 {{ $status === $key
                                     ? 'bg-white/20'
                                     : 'bg-slate-200 dark:bg-slate-700' }}">
                        {{ $counts[$key] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Users table --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
            @if ($users->isEmpty())
                <x-empty-state
                    title="Tidak ada user"
                    message="Belum ada user dengan status ini."
                    icon="users" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-5 py-3 font-medium">User</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                                <th class="px-5 py-3 font-medium">Daftar</th>
                                <th class="px-5 py-3 font-medium">Disetujui</th>
                                <th class="px-5 py-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($users as $u)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :user="$u" class="w-9 h-9" />
                                            <div>
                                                <p class="font-medium text-slate-900 dark:text-white">{{ $u->name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $u->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($u->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Disetujui
                                            </span>
                                        @elseif ($u->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-500/15 text-yellow-700 dark:text-yellow-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-500/15 text-rose-700 dark:text-rose-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs">
                                        {{ $u->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs">
                                        @if ($u->approved_at)
                                            {{ $u->approved_at->format('d M Y') }}
                                            @if ($u->approver)
                                                <p class="text-slate-400 dark:text-slate-500">oleh {{ $u->approver->name }}</p>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex justify-end gap-1">
                                            @if ($u->id === auth()->id())
                                                <span class="text-xs text-slate-400 italic px-2">Anda</span>
                                            @else
                                                @if ($u->status !== 'approved')
                                                    <div x-data="approveAction({ approveUrl: '{{ route('admin.users.approve', $u) }}', name: '{{ addslashes($u->name) }}' })" class="inline">
                                                        <button type="button"
                                                                @click="confirmApprove()"
                                                                class="px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-500/25"
                                                                title="Approve">
                                                            Approve
                                                        </button>
                                                        <form x-ref="approveForm" method="POST" action="{{ route('admin.users.approve', $u) }}" class="hidden">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                @endif
                                                {{-- Tolak hanya muncul saat status PENDING --}}
                                                @if ($u->status === 'pending')
                                                    <div x-data="rejectAction({ rejectUrl: '{{ route('admin.users.reject', $u) }}', name: '{{ addslashes($u->name) }}' })" class="inline">
                                                        <button type="button"
                                                                @click="confirmReject()"
                                                                class="px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-100 dark:bg-yellow-500/15 text-yellow-700 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-500/25"
                                                                title="Reject">
                                                            Tolak
                                                        </button>
                                                        <form x-ref="rejectForm" method="POST" action="{{ route('admin.users.reject', $u) }}" class="hidden">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                @endif

                                                {{-- Hapus selalu muncul (kecuali user sendiri) --}}
                                                <div x-data="deleteAction({ deleteUrl: '{{ route('admin.users.destroy', $u) }}', name: '{{ addslashes($u->name) }}' })" class="inline">
                                                    <button type="button"
                                                            @click="confirmDelete()"
                                                            class="px-2.5 py-1 rounded-md text-xs font-medium bg-rose-100 dark:bg-rose-500/15 text-rose-700 dark:text-rose-400 hover:bg-rose-200 dark:hover:bg-rose-500/25"
                                                            title="Hapus">
                                                        Hapus
                                                    </button>
                                                    <form x-ref="deleteForm" method="POST" action="{{ route('admin.users.destroy', $u) }}" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="px-5 py-4 border-t border-slate-200 dark:border-slate-800">
                        {{ $users->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
                window.approveAction = function ({ approveUrl, name }) {
            return {
                userName: name,
                approveUrl,

                confirmApprove() {
                    Alpine.store('confirm').show({
                        title: 'Setujui Pendaftaran?',
                        message: `User "${this.userName}" akan disetujui dan dapat mengakses panel admin. Email pemberitahuan akan dikirim otomatis.`,
                        confirmText: 'Setujui',
                        cancelText: 'Batal',
                        variant: 'default',
                        onConfirm: () => {
                            this.$refs.approveForm.submit();
                        }
                    });
                },
            };
        };

        window.rejectAction = function ({ rejectUrl, name }) {
            return {
                userName: name,
                rejectUrl,

                confirmReject() {
                    Alpine.store('confirm').show({
                        title: 'Tolak Pendaftaran?',
                        message: `Pendaftaran user "${this.userName}" akan ditolak. User tidak dapat login namun datanya tetap tersimpan.`,
                        confirmText: 'Tolak',
                        cancelText: 'Batal',
                        variant: 'danger',
                        onConfirm: () => {
                            this.$refs.rejectForm.submit();
                        }
                    });
                },
            };
        };

        window.deleteAction = function ({ deleteUrl, name }) {
            return {
                userName: name,
                deleteUrl,

                confirmDelete() {
                    Alpine.store('confirm').show({
                        title: 'Hapus User Permanen?',
                        message: `User "${this.userName}" akan dihapus secara permanen beserta semua data terkait. Tindakan ini tidak dapat dibatalkan.`,
                        confirmText: 'Hapus',
                        cancelText: 'Batal',
                        variant: 'danger',
                        onConfirm: () => {
                            this.$refs.deleteForm.submit();
                        }
                    });
                },
            };
        };
    </script>
    @endpush
</x-layouts.app>
