@props(['announcements'])


<section id="announcements" class="relative bg-gradient-to-b from-slate-50 via-white to-slate-50 pt-12 sm:pt-20 md:pt-30 pb-12 sm:pb-16 md:pb-24 px-3 sm:px-4 font-sans text-slate-900 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]">
        <div class="absolute inset-x-0 top-8 bottom-8" style="background-image: radial-gradient(circle, #0052CC 1px, transparent 1px); background-size: 30px 30px;"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-10 md:mb-12 lg:mb-16">
            <h2 class="font-poppins font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl tracking-tight text-[#0052CC] mb-2 sm:mb-3 md:mb-4">
                ANNOUNCEMENTS
            </h2>
            <div class="w-16 sm:w-20 md:w-24 h-1 md:h-1.5 bg-gradient-to-r from-[#0052CC] via-[#1565C0] to-[#FFD700] mx-auto rounded-full mb-3 sm:mb-4 md:mb-6"></div>
            <p class="text-slate-600 text-xs sm:text-sm md:text-base lg:text-base font-light max-w-2xl mx-auto leading-relaxed px-2 sm:px-4">Stay informed with the latest news and updates from Barangay Daang Bakal</p>
        </div>

        @php
            $activeAnnouncements = $announcements->filter(function ($announcement) {
                $endDate = isset($announcement->end_date) ? \Carbon\Carbon::parse($announcement->end_date) : null;

                return !$endDate || !$endDate->isPast();
            });
        @endphp

        @if($activeAnnouncements->count())
            <!-- Announcements Carousel -->
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 w-8 sm:w-12 bg-gradient-to-r from-slate-50 to-transparent z-10"></div>
                <div class="pointer-events-none absolute inset-y-0 right-0 w-8 sm:w-12 bg-gradient-to-l from-slate-50 to-transparent z-10"></div>

                <div class="announcements-carousel flex gap-6 md:gap-8 py-4 px-2 md:px-6 overflow-x-auto no-scrollbar snap-x snap-mandatory scroll-smooth">
                        @foreach($activeAnnouncements as $announcement)
                            @php
                                $createdDate = \Carbon\Carbon::parse($announcement->created_at);
                                $endDate = isset($announcement->end_date) ? \Carbon\Carbon::parse($announcement->end_date) : null;
                            @endphp
                            
                            <div class="flex snap-start min-w-[88%] sm:min-w-[58%] md:min-w-[44%] lg:min-w-[400px]">
                                <div class="group h-full w-full bg-white/80 backdrop-blur-md shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100 hover:-translate-y-1.5 ring-1 ring-transparent hover:ring-blue-200 rounded-2xl flex flex-col">
                                    <!-- Gradient Header -->
                                    <div class="relative h-1.5 bg-gradient-to-r from-[#0052CC] via-[#1E88E5] to-[#7C4DFF]"></div>
                                    
                                    <div class="p-5 md:p-7 flex flex-col flex-grow">
                                        <!-- Date & Status -->
                                        <div class="flex items-start justify-between mb-5">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-white/80 rounded-2xl p-3.5 border border-blue-100 shadow-sm">
                                                    <div class="text-3xl font-black text-[#0052CC] font-barlow leading-none">{{ $createdDate->format('d') }}</div>
                                                    <div class="text-[11px] text-blue-600 font-bold uppercase tracking-[0.2em] mt-1">{{ $createdDate->format('M') }}</div>
                                                </div>
                                            </div>

                                            @if($endDate)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                                    ACTIVE
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                                    ONGOING
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Title -->
                                        <h3 class="text-lg md:text-xl font-bold text-slate-900 mb-3 leading-tight group-hover:text-[#0052CC] transition-colors font-barlow">
                                            {{ $announcement->title }}
                                        </h3>
                                        
                                        <!-- Content Preview -->
                                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-5 flex-grow">
                                            {!! nl2br(e($announcement->content)) !!}
                                        </p>
                                        
                                        <!-- Expiration Info -->
                                        @if($endDate)
                                            <div class="flex items-center gap-2 text-xs text-slate-500 pt-4 border-t border-slate-100">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="font-semibold">Valid until:</span> 
                                                <span class="text-slate-700 font-medium">{{ $endDate->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                <div class="pointer-events-none absolute left-0 right-0 top-1/2 -translate-y-1/2 flex items-center justify-between px-0 z-20">
                    <button type="button" aria-label="Previous announcements" class="carousel-prev pointer-events-auto inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white/90 backdrop-blur hover:border-blue-300 hover:text-blue-700 transition shadow-sm -translate-x-6 sm:-translate-x-10">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button type="button" aria-label="Next announcements" class="carousel-next pointer-events-auto inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white/90 backdrop-blur hover:border-blue-300 hover:text-blue-700 transition shadow-sm translate-x-4 sm:translate-x-6">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @else
            <div class="text-center text-slate-600 text-base sm:text-md">No active announcements available.</div>
        @endif
        
        <!-- Footer Text -->
        <div class="mt-12 md:mt-16 text-center px-4">
            <a href="https://www.facebook.com/share/1C1z18ud4n/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 md:gap-3 px-4 md:px-6 py-2.5 md:py-3 bg-white/80 backdrop-blur-md rounded-full shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-300 transition-all duration-300">
                <svg class="w-4 md:w-5 h-4 md:h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <p class="text-xs md:text-sm text-slate-600 font-medium">For more updates, visit the <span class="font-bold text-slate-900">Official Facebook Page</span> of Barangay Daang Bakal</p>
            </a>
        </div>
    </div>
</section>

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
@endpush

<script>
    (function () {
        const carousel = document.querySelector('.announcements-carousel');
        const prev = document.querySelector('.carousel-prev');
        const next = document.querySelector('.carousel-next');

        if (!carousel || !prev || !next) return;

        const getScrollAmount = () => {
            const firstCard = carousel.querySelector('[class*="min-w-"]');
            if (firstCard) {
                const styles = getComputedStyle(carousel);
                const gap = parseFloat(styles.columnGap || styles.gap || 0);
                return firstCard.getBoundingClientRect().width + gap;
            }
            return carousel.clientWidth * 0.9;
        };

        prev.addEventListener('click', () => {
            carousel.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });

        next.addEventListener('click', () => {
            carousel.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        });
    })();
</script>