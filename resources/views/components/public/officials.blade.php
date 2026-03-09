<section id="officials" class="relative bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 py-12 sm:py-16 md:py-20 lg:py-24 px-3 sm:px-4 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]">
        <div class="absolute inset-x-0 top-8 bottom-8" style="background-image: radial-gradient(circle, #0052CC 1px, transparent 1px); background-size: 30px 30px;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-8 sm:mb-12 md:mb-16 lg:mb-20">
            <h2 class="font-sans font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl tracking-tight text-[#0052CC] mb-2 sm:mb-3 md:mb-4">
                BARANGAY OFFICIALS
            </h2>
            <div class="w-16 sm:w-20 md:w-24 h-1 md:h-1.5 bg-gradient-to-r from-[#0052CC] via-[#1565C0] to-[#FFD700] mx-auto rounded-full"></div>
        </div>

        @php
            $captain = $officials->first(function ($o) {
                return strcasecmp($o->position, 'Barangay Captain') === 0 || strcasecmp($o->position, 'Punong Barangay') === 0;
            });
            $others = $officials->filter(function ($o) use ($captain) {
                if (!$captain) {
                    return true;
                }

                return $o->id !== $captain->id;
            });
        @endphp

        @if($captain)
            @php
                $captainPhoto = $captain->photo_path
                    ? asset('storage/'.$captain->photo_path)
                    : 'https://via.placeholder.com/400x400.png?text=No+Photo';
            @endphp
            <div class="flex justify-center mb-8 sm:mb-12 md:mb-16">
                <div class="flex-shrink-0 w-48 sm:w-64 md:w-72">
                    <div class="group bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100 hover:-translate-y-2">
                        <div class="relative h-1 bg-gradient-to-r from-[#0052CC] to-[#1565C0]"></div>
                        <div class="p-3 sm:p-5 md:p-6">
                            <div class="relative mb-2 sm:mb-3 md:mb-4">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg md:rounded-xl blur opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                <div class="relative h-40 sm:h-56 md:h-64 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg md:rounded-xl overflow-hidden border-2 border-slate-100">
                                    <img src="{{ $captainPhoto }}" alt="{{ $captain->first_name }} {{ $captain->last_name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="font-sans font-bold text-xs sm:text-sm md:text-base text-slate-900 uppercase leading-tight mb-2">
                                    {{ $captain->first_name }} {{ $captain->middle_initial }}. {{ $captain->last_name }}
                                </h3>
                                <div class="inline-flex items-center gap-1 md:gap-1.5 px-2.5 md:px-3 py-0.5 md:py-1 bg-blue-50 rounded-full border border-blue-100">
                                    <span class="text-[10px] md:text-xs font-semibold text-[#0052CC] uppercase tracking-wide">Barangay Captain</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($others->count())
            <div class="relative">
                <div id="officials-carousel" class="overflow-x-auto no-scrollbar px-3 sm:px-4 md:px-12">
                    <div class="flex gap-3 sm:gap-4 md:gap-6 py-4 px-2 md:px-6">
                        @foreach($others as $official)
                            @php
                                $photo = $official->photo_path
                                    ? asset('storage/'.$official->photo_path)
                                    : 'https://via.placeholder.com/400x400.png?text=No+Photo';
                            @endphp
                            <div class="flex-shrink-0 w-48 sm:w-64 md:w-72">
                                <div class="group bg-white rounded-lg sm:rounded-xl md:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100 hover:-translate-y-2">
                                    <div class="relative h-1 bg-gradient-to-r from-[#0052CC] to-[#1565C0]"></div>
                                    <div class="p-3 sm:p-5 md:p-6">
                                        <div class="relative mb-2 sm:mb-3 md:mb-4">
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg md:rounded-xl blur opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                            <div class="relative h-40 sm:h-56 md:h-64 bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg md:rounded-xl overflow-hidden border-2 border-slate-100">
                                                <img src="{{ $photo }}" alt="{{ $official->first_name }} {{ $official->last_name }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <h3 class="font-sans font-bold text-xs sm:text-sm md:text-base text-slate-900 uppercase leading-tight mb-2">
                                                {{ $official->first_name }} {{ $official->middle_initial }}. {{ $official->last_name }}
                                            </h3>
                                            <div class="inline-flex items-center gap-1 md:gap-1.5 px-2.5 md:px-3 py-0.5 md:py-1 bg-blue-50 rounded-full border border-blue-100">
                                                <span class="text-[10px] md:text-xs font-semibold text-[#0052CC] uppercase tracking-wide">{{ $official->position }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif(!$captain)
            <div class="text-center text-slate-600 text-base sm:text-md">No officials available.</div>
        @endif
    </div>
</section>

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
@endpush

<script>
    (function() {
        const container = document.getElementById('officials-carousel');

        if (!container) return;

        let autoScrollDirection = 1;
        let animationFrameId = null;
        let isPaused = false;
        let resumeTimeout = null;

        const getMaxScrollLeft = () => Math.max(0, container.scrollWidth - container.clientWidth);

        const step = () => {
            if (isPaused) {
                animationFrameId = requestAnimationFrame(step);
                return;
            }

            const maxScrollLeft = getMaxScrollLeft();
            if (maxScrollLeft <= 0) {
                animationFrameId = requestAnimationFrame(step);
                return;
            }

            if (container.scrollLeft >= maxScrollLeft - 1) {
                autoScrollDirection = -1;
            } else if (container.scrollLeft <= 1) {
                autoScrollDirection = 1;
            }

            container.scrollLeft += autoScrollDirection * 0.8;
            animationFrameId = requestAnimationFrame(step);
        };

        const pauseAutoScroll = () => {
            isPaused = true;
            if (resumeTimeout) {
                clearTimeout(resumeTimeout);
            }
        };

        const resumeAutoScroll = (delay = 1200) => {
            if (resumeTimeout) {
                clearTimeout(resumeTimeout);
            }
            resumeTimeout = setTimeout(() => {
                isPaused = false;
            }, delay);
        };

        container.addEventListener('mouseenter', pauseAutoScroll);
        container.addEventListener('mouseleave', () => resumeAutoScroll(200));
        container.addEventListener('touchstart', pauseAutoScroll, { passive: true });
        container.addEventListener('touchend', () => resumeAutoScroll(1200), { passive: true });
        container.addEventListener('scroll', () => resumeAutoScroll(1200), { passive: true });

        window.addEventListener('resize', () => {
            const maxScrollLeft = getMaxScrollLeft();
            if (container.scrollLeft > maxScrollLeft) {
                container.scrollLeft = maxScrollLeft;
            }
        });

        animationFrameId = requestAnimationFrame(step);
    })();
</script>