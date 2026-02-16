<div class="bg-[#2c4356] text-white py-2 px-4 shadow-inner border-b border-[#1f303d] relative z-40 fixed top-20 md:top-24 left-0 right-0 w-full">
    <div class="max-w-[1920px] mx-auto flex items-center">
        <span class="font-bold text-white text-sm sm:text-base mr-4 whitespace-nowrap z-10 bg-[#2c4356] pr-2">
            Latest Announcements:
        </span>
        <div class="flex-1 overflow-hidden relative h-6">
            <div class="absolute w-full animate-marquee whitespace-nowrap">
                @forelse(($announcements ?? []) as $a)
                    <span class="text-[#facc15] font-medium text-sm sm:text-base tracking-wide mx-4">
                        {{ $a->title }}: {{ $a->content }}
                    </span>
                    @if(!$loop->last)
                        <span class="text-white mx-2">-</span>
                    @endif
                @empty
                    <span class="text-[#facc15] font-medium text-sm sm:text-base tracking-wide mx-4">
                        There are no announcements at the moment.
                    </span>
                @endforelse
            </div>
        </div>
    </div>
</div>


@push('styles')
	<link rel="stylesheet" href="{{ asset('css/animations.css') }}">
@endpush