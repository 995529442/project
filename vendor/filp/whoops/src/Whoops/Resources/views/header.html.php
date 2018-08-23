<div class="exception">
    <div class="exc-title">
        <?php foreach ($name as $i => $nameSection): ?>
            <?php if ($i == count($name) - 1): ?>
                <span class="exc-title-primary"><?php echo $tpl->escape($nameSection) ?></span>
            <?php else: ?>
                <?php echo $tpl->escape($nameSection) . ' \\' ?>
            <?php endif ?>
        <?php endforeach ?>
        <?php if ($code): ?>
            <span title="Exception Code">(<?php echo $tpl->escape($code) ?>)</span>
        <?php endif ?>
    </div>

    <div class="exc-message">
        <?php if (!empty($message)): ?>
            <span><?php echo $tpl->escape($message) ?></span>
        <?php else: ?>
            <span class="exc-message-empty-notice">No message</span>
        <?php endif ?>

        <ul class="search-for-help">
            <?php if (!empty($docref_url)): ?>
                <li>
                    <a rel="noopener noreferrer" target="_blank" href="<?php echo $docref_url; ?>"
                       title="Search for help in the PHP manual.">
                        <!-- PHP icon by Icons Solid -->
                        <!-- https://www.iconfinder.com/icons/322421/book_icon -->
                        <!-- Free for commercial use -->
                        <svg height="16px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1"
                             viewBox="0 0 32 32" width="16px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(240 0)">
                                <path d="M-211,4v26h-24c-1.104,0-2-0.895-2-2s0.896-2,2-2h22V0h-22c-2.209,0-4,1.791-4,4v24c0,2.209,1.791,4,4,4h26V4H-211z    M-235,8V2h20v22h-20V8z M-219,6h-12V4h12V6z M-223,10h-8V8h8V10z M-227,14h-4v-2h4V14z"/>
                            </g></svg>
                    </a>
                </li>
            <?php endif ?>
            <li>
                <a rel="noopener noreferrer" target="_blank"
                   href="https://google.com/search?q=<?php echo urlencode(implode('\\', $name) . ' ' . $message) ?>"
                   title="Search for help on Google.">
                    <!-- Google icon by Alfredo H, from https://www.iconfinder.com/alfredoh -->
                    <!-- Creative Commons (Attribution 3.0 Unported) -->
                    <!-- http://creativecommons.org/licenses/by/3.0/ -->
                    <svg class="google" height="16" viewBox="0 0 512 512" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M457.732 216.625c2.628 14.04 4.063 28.743 4.063 44.098C461.795 380.688 381.48 466 260.205 466c-116.024 0-210-93.977-210-210s93.976-210 210-210c56.703 0 104.076 20.867 140.44 54.73l-59.205 59.197v-.135c-22.046-21.002-50-31.762-81.236-31.762-69.297 0-125.604 58.537-125.604 127.84 0 69.29 56.306 127.97 125.604 127.97 62.87 0 105.653-35.966 114.46-85.313h-114.46v-81.902h197.528z"/>
                    </svg>
                </a>
            </li>
            <li>
                <a rel="noopener noreferrer" target="_blank"
                   href="https://duckduckgo.com/?q=<?php echo urlencode(implode('\\', $name) . ' ' . $message) ?>"
                   title="Search for help on DuckDuckGo.">
                    <!-- DuckDuckGo icon by IconBaandar Team, from https://www.iconfinder.com/iconbaandar -->
                    <!-- Creative Commons (Attribution 3.0 Unported) -->
                    <!-- http://creativecommons.org/licenses/by/3.0/ -->
                    <svg class="duckduckgo" height="16" viewBox="150 150 1675 1675" width="16"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M1792 1024c0 204.364-80.472 398.56-224.955 543.04-144.483 144.48-338.68 224.95-543.044 224.95-204.36 0-398.56-80.47-543.04-224.95-144.48-144.482-224.95-338.676-224.95-543.04 0-204.365 80.47-398.562 224.96-543.045C625.44 336.47 819.64 256 1024 256c204.367 0 398.565 80.47 543.05 224.954C1711.532 625.437 1792 819.634 1792 1024zm-270.206 497.787C1654.256 1389.327 1728 1211.36 1728 1024c0-187.363-73.74-365.332-206.203-497.796C1389.332 393.74 1211.363 320 1024 320s-365.33 73.742-497.795 206.205C393.742 658.67 320 836.637 320 1024c0 187.36 73.744 365.326 206.206 497.787C658.67 1654.25 836.638 1727.99 1024 1727.99c187.362 0 365.33-73.74 497.794-206.203z"/>
                        <path d="M1438.64 1177.41c0-.03-.005-.017-.01.004l.01-.004z"/>
                        <path d="M1499.8 976.878c.03-.156-.024-.048-.11.107l.11-.107z"/>
                        <path d="M1105.19 991.642zm-68.013-376.128c-8.087-10.14-18.028-19.965-29.89-29.408-13.29-10.582-29-20.76-47.223-30.443-35.07-18.624-74.482-31.61-115.265-38.046-39.78-6.28-80.84-6.256-120.39.917l1.37 31.562c1.8.164 7.7 3.9 14.36 8.32-20.68 5.94-39.77 14.447-39.48 39.683l.2 17.48 17.3-1.73c29.38-2.95 60.17-2.06 90.32 2.61 9.21 1.42 18.36 3.2 27.38 5.32l-4.33 1.15c-20.45 5.58-38.93 12.52-54.25 20.61-46.28 24.32-75.51 60.85-90.14 108.37-14.14 45.95-14.27 101.81-2.72 166.51l.06.06c15.14 84.57 64.16 316.39 104.11 505.39 19.78 93.59 37.38 176.83 47.14 224.4 3.26 15.84 5.03 31.02 5.52 45.52.3 9.08.09 17.96-.58 26.62-.45 5.8-1.11 11.51-1.96 17.112l31.62 4.75c.71-4.705 1.3-9.494 1.76-14.373 48.964 10.517 99.78 16.05 151.88 16.05 60.68 0 119.61-7.505 175.91-21.64 3.04 6.08 6.08 12.19 9.11 18.32l28.62-14.128c-2.11-4.27-4.235-8.55-6.37-12.84-23.005-46.124-47.498-93.01-68.67-133.534-15.39-29.466-29.01-55.53-39.046-75.58-26.826-53.618-53.637-119.47-68.28-182.368-8.78-37.705-13.128-74.098-10.308-105.627-15.31-6.28-26.69-11.8-31.968-15.59l-.01.015c-14.22-10.2-31.11-28.12-41.82-49.717-8.618-17.376-13.4-37.246-10.147-57.84 3.17-19.84 27.334-46.714 57.843-67.46v-.063c26.554-18.05 58.75-32.506 86.32-34.31 7.835-.51 16.31-1.008 23.99-1.45 33.45-1.95 50.243-2.93 84.475-11.42 10.88-2.697 26.19-6.56 43.53-11.09 2.364-40.7-5.947-87.596-21.04-133.234-22.004-66.53-58.68-131.25-97.627-170.21-12.543-12.55-28.17-22.79-45.9-30.933-16.88-7.753-35.64-13.615-55.436-17.782zm-10.658 178.553s6.77-42.485 58.39-33.977c27.96 4.654 37.89 29.833 37.89 29.833s-25.31-14.46-44.95-14.198c-40.33.53-51.35 18.342-51.35 18.342zm-240.45-18.802c48.49-19.853 72.11 11.298 72.11 11.298s-35.21-15.928-69.46 5.59c-34.19 21.477-32.92 43.452-32.92 43.452s-18.17-40.5 30.26-60.34zm296.5 95.4c0-6.677 2.68-12.694 7.01-17.02 4.37-4.37 10.42-7.074 17.1-7.074 6.73 0 12.79 2.7 17.15 7.05 4.33 4.33 7.01 10.36 7.01 17.05 0 6.74-2.7 12.81-7.07 17.18-4.33 4.33-10.37 7.01-17.1 7.01-6.68 0-12.72-2.69-17.05-7.03-4.36-4.37-7.07-10.43-7.07-17.16zm-268.42 51.27c0-8.535 3.41-16.22 8.93-21.738 5.55-5.55 13.25-8.982 21.81-8.982 8.51 0 16.18 3.415 21.7 8.934 5.55 5.55 8.98 13.25 8.98 21.78 0 8.53-3.44 16.23-8.98 21.79-5.52 5.52-13.19 8.93-21.71 8.93-8.55 0-16.26-3.43-21.82-8.99-5.52-5.52-8.93-13.2-8.93-21.74z"/>
                        <path d="M1102.48 986.34zm390.074-64.347c-28.917-11.34-74.89-12.68-93.32-3.778-11.5 5.567-35.743 13.483-63.565 21.707-25.75 7.606-53.9 15.296-78.15 21.702-17.69 4.67-33.3 8.66-44.4 11.435-34.92 8.76-52.05 9.77-86.17 11.78-7.84.46-16.48.97-24.48 1.5-28.12 1.86-60.97 16.77-88.05 35.4v.06c-31.12 21.4-55.77 49.12-59.01 69.59-3.32 21.24 1.56 41.74 10.35 59.67 10.92 22.28 28.15 40.77 42.66 51.29l.01-.02c5.38 3.9 16.98 9.6 32.6 16.08 26.03 10.79 63.2 23.76 101.25 34.23 43.6 11.99 89.11 21.05 121.69 20.41 34.26-.69 77.73-10.52 114.54-24.67 22.15-8.52 42.21-18.71 56.88-29.58 17.85-13.22 28.7-28.42 28.4-44.74-.07-3.89-.72-7.63-1.97-11.21l-.02.01c-11.6-33.06-50.37-23.59-105.53-10.12-46.86 11.445-107.94 26.365-169.01 20.434-32.56-3.167-54.45-10.61-67.88-20.133-5.96-4.224-9.93-8.67-12.18-13.11-1.96-3.865-2.68-7.84-2.33-11.714.39-4.42 2.17-9.048 5.1-13.57l-.05-.03c7.86-12.118 23.082-9.72 43.93-6.43 25.91 4.08 58.2 9.172 99.013-3.61 39.63-12.378 87.76-29.9 131.184-47.39 42.405-17.08 80.08-34.078 100.74-46.18 25.46-14.87 37.57-29.428 40.59-42.866 2.725-12.152-.89-22.48-8.903-31.07-5.87-6.29-14.254-11.31-23.956-15.115z"/>
                    </svg>
                </a>
            </li>
            <li>
                <a rel="noopener noreferrer" target="_blank"
                   href="https://stackoverflow.com/search?q=<?php echo urlencode(implode('\\', $name) . ' ' . $message) ?>"
                   title="Search for help on Stack Overflow.">
                    <!-- Stack Overflow icon by Picons.me, from https://www.iconfinder.com/Picons -->
                    <!-- Free for commercial use -->
                    <svg class="stackoverflow" height="16" viewBox="-1163 1657.697 56.693 56.693" width="16"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M-1126.04 1689.533l-16.577-9.778 2.088-3.54 16.578 9.778zM-1127.386 1694.635l-18.586-4.996 1.068-3.97 18.586 4.995zM-1127.824 1700.137l-19.165-1.767.378-4.093 19.165 1.767zM-1147.263 1701.293h19.247v4.11h-19.247z"/>
                        <path d="M-1121.458 1710.947s0 .96-.032.96v.016h-30.796s-.96 0-.96-.016h-.032v-20.03h3.288v16.805h25.244v-16.804h3.288v19.07zM-1130.667 1667.04l10.844 15.903-3.396 2.316-10.843-15.903zM-1118.313 1663.044l3.29 18.963-4.05.703-3.29-18.963z"/>
                    </svg>
                </a>
            </li>
        </ul>

        <span id="plain-exception"><?php echo $tpl->escape($plain_exception) ?></span>
        <button id="copy-button" class="clipboard" data-clipboard-text="<?php echo $tpl->escape($plain_exception) ?>"
                title="Copy exception details to clipboard">
            COPY
        </button>
    </div>
</div>
