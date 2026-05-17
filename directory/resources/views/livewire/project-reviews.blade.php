<div class="mt-8">
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-2xl font-bold mb-6">User Experience</h3>

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('message') }}
            </div>
        @endif

        @auth
            <form wire:submit.prevent="submitReview" class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="font-bold mb-4">Write your Experience</h4>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Your experience about {{ $project->name }}</label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                             <input type="radio" wire:model="sentiment" value="positive" class="hidden peer">
                             <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 transition-all">
                                 <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                             </div>
                             <span class="text-sm font-semibold text-gray-600 group-hover:text-green-600 peer-checked:text-green-600">Positive</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer group">
                             <input type="radio" wire:model="sentiment" value="neutral" class="hidden peer">
                             <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center peer-checked:border-gray-500 peer-checked:bg-gray-500 transition-all">
                                 <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                             </div>
                             <span class="text-sm font-semibold text-gray-600 group-hover:text-gray-900 peer-checked:text-gray-900">Neutral</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer group">
                             <input type="radio" wire:model="sentiment" value="negative" class="hidden peer">
                             <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center peer-checked:border-red-500 peer-checked:bg-red-500 transition-all">
                                 <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                             </div>
                             <span class="text-sm font-semibold text-gray-600 group-hover:text-red-600 peer-checked:text-red-600">Negative</span>
                        </label>
                    </div>
                    @error('sentiment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Title (Optional)</label>
                    <input wire:model="title" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Brief summary of your experience">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Detailed Feedback</label>
                    <textarea wire:model="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32" placeholder="Tell us more about your experience..."></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition">
                    Submit Feedback
                </button>
            </form>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
                <p class="text-blue-700">Please <a href="/login" class="font-bold underline">login</a> via the forum to leave feedback.</p>
            </div>
        @endauth

        <div class="space-y-6">
            @foreach($reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-0" wire:key="review-{{ $review->id }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</span>
                            <span class="text-gray-400 text-xs">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div>
                            @if($review->sentiment === 'positive')
                                <span class="px-2 py-1 text-[10px] font-bold uppercase bg-green-100 text-green-700 rounded border border-green-200">Positive</span>
                            @elseif($review->sentiment === 'negative')
                                <span class="px-2 py-1 text-[10px] font-bold uppercase bg-red-100 text-red-700 rounded border border-red-200">Negative</span>
                            @else
                                <span class="px-2 py-1 text-[10px] font-bold uppercase bg-gray-100 text-gray-700 rounded border border-gray-200">Neutral</span>
                            @endif
                        </div>
                    </div>
                    @if($review->title)
                        <h5 class="font-bold text-gray-800 mb-1">{{ $review->title }}</h5>
                    @endif
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $review->content }}</p>
                </div>
            @endforeach

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>