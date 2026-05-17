<?php
$file = 'c:/wamp64/www/wlc/directory/resources/views/livewire/project-list.blade.php';
$content = file_get_contents($file);

// 1. Log Verifiable False border
$content = preg_replace(
    '/(class="px-2 py-0.5 text-\[9px\] bg-\[var\(--card-log-false-bg\)\] text-\[var\(--card-log-false-text\)\] border )border-gray-700\/50/',
    '$1border-[var(--card-log-false-border)]',
    $content
);

// 2. TOR True border
$content = preg_replace(
    '/(class="px-2 py-0.5 rounded text-\[9px\] font-bold uppercase w-fit relative cursor-help bg-\[var\(--card-tor-true-bg\)\] text-\[var\(--card-tor-true-text\)\])"/',
    '$1 border border-[var(--card-tor-true-border)]"',
    $content
);

// 3. TOR False border
$content = preg_replace(
    '/(class="px-2 py-0.5 rounded text-\[9px\] font-bold uppercase w-fit relative cursor-help bg-\[var\(--card-attr-bg\)\] text-\[var\(--card-tor-false-text\)\])"/',
    '$1 border border-[var(--card-tor-false-border)]"',
    $content
);

file_put_contents($file, $content);
echo "Replacements complete.\n";
