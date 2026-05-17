<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a></li>
                            <li class="flex items-center space-x-2">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium text-gray-900">{{ $page->title }}</span>
                            </li>
                        </ol>
                    </nav>

                    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $page->title }}</h1>

                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed summernote-content transition-all duration-300">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Ensure Summernote content looks good */
        .summernote-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        .summernote-content iframe {
            max-width: 100%;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        .summernote-content table {
            width: 100% !important;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }
        .summernote-content table td, .summernote-content table th {
            border: 1px solid #e5e7eb;
            padding: 0.75rem;
        }
        .summernote-content ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .summernote-content ol {
            list-style-type: decimal;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</div>
