<x-layouts.app :title="__('content.page_title')">
    <x-slot:header>{{ __('content.title') }}</x-slot:header>

    <div x-data="contentPage()" class="space-y-6">

        {{-- TAB BAR --}}
        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 flex-wrap">
            <button @click="tab = 'research'"
                    :class="tab === 'research' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <x-icon name="beaker" class="w-4 h-4" /> {{ __('content.tab.research') }}
            </button>
            <button @click="tab = 'partners'"
                    :class="tab === 'partners' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <x-icon name="handshake" class="w-4 h-4" /> {{ __('content.tab.partners') }}
            </button>
            <button @click="tab = 'gallery'"
                    :class="tab === 'gallery' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <x-icon name="photo" class="w-4 h-4" /> {{ __('content.tab.gallery') }}
            </button>
        </div>

        {{-- ========================================================
             TAB 1: R&D — Tim Peneliti
             ======================================================== --}}
        <div x-show="tab === 'research'" class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('content.research.admin_title') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('content.research.admin_subtitle') }}</p>
                </div>
                <button @click="openResearcher()"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    <x-icon name="plus" class="w-4 h-4" /> {{ __('content.research.add_btn') }}
                </button>
            </div>

            @if ($researchers->isEmpty())
                <x-empty-state :title="__('content.research.team_empty')" message="" icon="users" />
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($researchers as $person)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 text-center">
                            @if ($person->photo_path)
                                <img src="{{ asset('storage/' . $person->photo_path) }}" alt="{{ $person->name }}"
                                     class="w-16 h-16 rounded-full object-cover mx-auto mb-3 ring-2 ring-emerald-100 dark:ring-emerald-500/20">
                            @else
                                <div class="w-16 h-16 rounded-full mx-auto mb-3 ring-2 ring-emerald-100 dark:ring-emerald-500/20 bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-xl font-bold">
                                    {{ strtoupper(mb_substr($person->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ $person->name }}</h3>
                            @if ($person->role)
                                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $person->role }}</p>
                            @endif
                            @if ($person->affiliation)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $person->affiliation }}</p>
                            @endif
                            <div class="flex justify-center gap-1 mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                                <button @click='openResearcher(@json($person))'
                                        class="p-1.5 rounded-md text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition" title="{{ __('messages.action.edit') }}">
                                    <x-icon name="edit" class="w-4 h-4" />
                                </button>
                                <form method="POST" action="{{ route('admin.content.researcher.destroy', $person) }}"
                                      onsubmit="return confirm('{{ __('content.research.confirm_delete') }}')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-md text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition" title="{{ __('messages.action.delete') }}">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========================================================
             TAB 2: PARTNERS
             ======================================================== --}}
        <div x-show="tab === 'partners'" x-cloak class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('content.partners.title') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $partners->count() }} partner</p>
                </div>
                <button @click="openPartner()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    <x-icon name="plus" class="w-4 h-4" /> {{ __('content.partners.add_btn') }}
                </button>
            </div>

            @if ($partners->isEmpty())
                <x-empty-state :title="__('content.partners.empty')" message="" icon="inbox" />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($partners as $p)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
                            <div class="flex items-start gap-3 mb-3">
                                @if ($p->logo_path)
                                    <div class="w-12 h-12 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center p-1.5 shrink-0">
                                        <img src="{{ asset('storage/' . $p->logo_path) }}" alt="{{ $p->name }}" class="max-h-full max-w-full object-contain">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold shrink-0">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ $p->name }}</h3>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ __('content.partners.categories.' . $p->category) }}</span>
                                </div>
                                @unless ($p->is_active)
                                    <span class="px-2 py-0.5 rounded text-xs bg-slate-100 dark:bg-slate-700 text-slate-500">Off</span>
                                @endunless
                            </div>
                            @if ($p->description)
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2 mb-3">{{ $p->description }}</p>
                            @endif
                            <div class="flex justify-end gap-1 pt-2 border-t border-slate-100 dark:border-slate-800">
                                <button @click='openPartner(@json($p))'
                                        class="p-1.5 rounded-md text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition">
                                    <x-icon name="edit" class="w-4 h-4" />
                                </button>
                                <form method="POST" action="{{ route('admin.content.partner.destroy', $p) }}"
                                      onsubmit="return confirm('{{ __('content.partners.confirm_delete') }}')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-md text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========================================================
             TAB 3: GALLERY
             ======================================================== --}}
        <div x-show="tab === 'gallery'" x-cloak class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('content.gallery.title') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $galleries->count() }} foto</p>
                </div>
                <button @click="openGallery()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
                    <x-icon name="plus" class="w-4 h-4" /> {{ __('content.gallery.add_btn') }}
                </button>
            </div>

            @if ($galleries->isEmpty())
                <x-empty-state :title="__('content.gallery.empty')" message="" icon="inbox" />
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($galleries as $g)
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                            <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800">
                                <img src="{{ asset('storage/' . $g->image_path) }}" alt="{{ $g->title }}" loading="lazy" class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h3 class="font-medium text-slate-900 dark:text-white text-sm truncate">{{ $g->title }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ __('content.gallery.categories.' . $g->category) }}{{ $g->location ? ' · ' . $g->location : '' }}</p>
                                <div class="flex justify-end gap-1 mt-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                    <button @click='openGallery(@json($g))'
                                            class="p-1.5 rounded-md text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition">
                                        <x-icon name="edit" class="w-4 h-4" />
                                    </button>
                                    <form method="POST" action="{{ route('admin.content.gallery.destroy', $g) }}"
                                          onsubmit="return confirm('{{ __('content.gallery.confirm_delete') }}')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-md text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========================================================
             MODAL: RESEARCHER FORM
             ======================================================== --}}
        <div x-show="modal === 'researcher'" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @click.self="modal = null">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900">
                    <h3 class="font-bold text-slate-900 dark:text-white" x-text="researcherForm.id ? @js(__('content.research.form_title_edit')) : @js(__('content.research.form_title_add'))"></h3>
                    <button @click="modal = null" class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"><x-icon name="x" class="w-5 h-5" /></button>
                </div>
                <form method="POST" :action="researcherForm.id ? `{{ url('admin/konten/peneliti') }}/${researcherForm.id}` : '{{ route('admin.content.researcher.store') }}'" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <template x-if="researcherForm.id"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.name') }} *</label>
                        <input type="text" name="name" x-model="researcherForm.name" required
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.role') }}</label>
                            <input type="text" name="role" x-model="researcherForm.role" placeholder="Ketua Peneliti"
                                   class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.sort_order') }}</label>
                            <input type="number" name="sort_order" x-model="researcherForm.sort_order" min="0"
                                   class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('content.research.affiliation') ?? 'Afiliasi' }}</label>
                        <input type="text" name="affiliation" x-model="researcherForm.affiliation" placeholder="Dosen / Mahasiswa — Universitas Nusa Putra"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Profil / Scholar URL</label>
                        <input type="url" name="scholar_url" x-model="researcherForm.scholar_url" placeholder="https://..."
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.image') }}</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-sm text-slate-600 dark:text-slate-400 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-700 dark:file:text-emerald-400 file:text-sm file:font-medium">
                        <p class="text-xs text-slate-400 mt-1">{{ __('content.research.photo_hint') }}</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="is_active" value="1" x-model="researcherForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        {{ __('messages.label.active') }}
                    </label>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="modal = null" class="flex-1 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">{{ __('messages.action.cancel') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">{{ __('messages.action.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================
             MODAL: PARTNER FORM
             ======================================================== --}}
        <div x-show="modal === 'partner'" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @click.self="modal = null">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900">
                    <h3 class="font-bold text-slate-900 dark:text-white" x-text="partnerForm.id ? @js(__('content.partners.form_title_edit')) : @js(__('content.partners.form_title_add'))"></h3>
                    <button @click="modal = null" class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"><x-icon name="x" class="w-5 h-5" /></button>
                </div>
                <form method="POST" :action="partnerForm.id ? `{{ url('admin/konten/partner') }}/${partnerForm.id}` : '{{ route('admin.content.partner.store') }}'" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <template x-if="partnerForm.id"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.name') }} *</label>
                        <input type="text" name="name" x-model="partnerForm.name" required
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.description') }}</label>
                        <textarea name="description" x-model="partnerForm.description" rows="3"
                                  class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.category') }}</label>
                            <select name="category" x-model="partnerForm.category"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                                <option value="institution">{{ __('content.partners.categories.institution') }}</option>
                                <option value="ngo">{{ __('content.partners.categories.ngo') }}</option>
                                <option value="government">{{ __('content.partners.categories.government') }}</option>
                                <option value="university">{{ __('content.partners.categories.university') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.website') }}</label>
                            <input type="url" name="website" x-model="partnerForm.website" placeholder="https://..."
                                   class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.logo') }}</label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-sm text-slate-600 dark:text-slate-400 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-700 dark:file:text-emerald-400 file:text-sm file:font-medium">
                        <p class="text-xs text-slate-400 mt-1" x-show="partnerForm.id && partnerForm.logo_path">Logo lama dipertahankan jika tidak diganti.</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="is_active" value="1" x-model="partnerForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        {{ __('messages.label.active') }}
                    </label>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="modal = null" class="flex-1 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">{{ __('messages.action.cancel') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">{{ __('messages.action.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================
             MODAL: GALLERY FORM
             ======================================================== --}}
        <div x-show="modal === 'gallery'" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @click.self="modal = null">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-800 sticky top-0 bg-white dark:bg-slate-900">
                    <h3 class="font-bold text-slate-900 dark:text-white" x-text="galleryForm.id ? @js(__('content.gallery.form_title_edit')) : @js(__('content.gallery.form_title_add'))"></h3>
                    <button @click="modal = null" class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"><x-icon name="x" class="w-5 h-5" /></button>
                </div>
                <form method="POST" :action="galleryForm.id ? `{{ url('admin/konten/galeri') }}/${galleryForm.id}` : '{{ route('admin.content.gallery.store') }}'" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <template x-if="galleryForm.id"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.title') }} *</label>
                        <input type="text" name="title" x-model="galleryForm.title" required
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.description') }}</label>
                        <textarea name="description" x-model="galleryForm.description" rows="2"
                                  class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.location') }}</label>
                            <input type="text" name="location" x-model="galleryForm.location"
                                   class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.category') }}</label>
                            <select name="category" x-model="galleryForm.category"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                                <option value="field">{{ __('content.gallery.categories.field') }}</option>
                                <option value="lab">{{ __('content.gallery.categories.lab') }}</option>
                                <option value="event">{{ __('content.gallery.categories.event') }}</option>
                                <option value="other">{{ __('content.gallery.categories.other') }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.taken_at') }}</label>
                        <input type="date" name="taken_at" x-model="galleryForm.taken_at"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">{{ __('messages.label.image') }} <span x-show="!galleryForm.id">*</span></label>
                        <input type="file" name="image" accept="image/*" :required="!galleryForm.id"
                               class="w-full text-sm text-slate-600 dark:text-slate-400 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-700 dark:file:text-emerald-400 file:text-sm file:font-medium">
                        <p class="text-xs text-slate-400 mt-1">{{ __('content.gallery.image_hint') }}</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="is_published" value="1" x-model="galleryForm.is_published" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        {{ __('messages.label.published') }}
                    </label>
                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="modal = null" class="flex-1 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">{{ __('messages.action.cancel') }}</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">{{ __('messages.action.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        window.contentPage = function () {
            return {
                tab: @json($tab),
                modal: null,

                researcherForm: {},
                partnerForm: {},
                galleryForm: {},

                openResearcher(data = null) {
                    this.researcherForm = data ? {
                        id: data.id, name: data.name, role: data.role, affiliation: data.affiliation,
                        scholar_url: data.scholar_url, sort_order: data.sort_order, is_active: !!data.is_active,
                    } : { is_active: true, sort_order: 0 };
                    this.modal = 'researcher';
                },

                openPartner(data = null) {
                    this.partnerForm = data ? {
                        id: data.id, name: data.name, description: data.description, website: data.website,
                        category: data.category, logo_path: data.logo_path, is_active: !!data.is_active,
                    } : { category: 'institution', is_active: true };
                    this.modal = 'partner';
                },

                openGallery(data = null) {
                    this.galleryForm = data ? {
                        id: data.id, title: data.title, description: data.description, location: data.location,
                        taken_at: data.taken_at ? data.taken_at.substring(0,10) : '', category: data.category,
                        is_published: !!data.is_published,
                    } : { category: 'field', is_published: true };
                    this.modal = 'gallery';
                },
            };
        };
    </script>
    @endpush
</x-layouts.app>
