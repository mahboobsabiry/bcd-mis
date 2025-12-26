<div class="compact-org-chart">
    <!-- Chart Header -->
    <div class="chart-header mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-sitemap mr-2"></i>
                    @if(app()->getLocale() == 'en')
                        Organization Chart
                    @else
                        چارت تشکیلاتی
                    @endif
                </h6>
            </div>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-primary" onclick="chartZoom(0.9)">
                    <i class="fas fa-search-minus"></i>
                </button>
                <button class="btn btn-outline-primary" onclick="chartZoom(1)">
                    <i class="fas fa-expand-arrows-alt"></i>
                </button>
                <button class="btn btn-outline-primary" onclick="chartZoom(1.1)">
                    <i class="fas fa-search-plus"></i>
                </button>
                <button class="btn btn-success" onclick="printChart()">
                    <i class="fas fa-print"></i>
                    @if(app()->getLocale() == 'en')
                        Print
                    @else
                        چاپ
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="stats-summary mb-3" id="statsForPrint">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">
                <i class="fas fa-chart-pie mr-2"></i>
                @if(app()->getLocale() == 'en')
                    Position Statistics by Grade
                @else
                    آمار بست‌ها بر اساس درجه
                @endif
            </h6>
            <span class="badge badge-primary">
                {{ \App\Models\Office\Position::count() }}
                @if(app()->getLocale() == 'en')
                    Total Positions
                @else
                    کل بست‌ها
                @endif
            </span>
        </div>

        <div class="row">
            @php
                // Calculate position statistics by grade
                $positionStats = [];
                $allPositions = \App\Models\Office\Position::all();

                foreach($allPositions as $position) {
                    $grade = $position->position_number;
                    if(!isset($positionStats[$grade])) {
                        $positionStats[$grade] = [
                            'count' => 0,
                            'filled' => 0,
                            'vacant' => 0,
                            'title' => 'بست ' . $grade
                        ];
                    }
                    $positionStats[$grade]['count']++;

                    $filled = $position->codes()->whereHas('employee')->count();
                    $positionStats[$grade]['filled'] += $filled;
                    $positionStats[$grade]['vacant'] += ($position->num_of_pos - $filled);
                }

                // Sort by grade and get top 6 grades
                ksort($positionStats);
                $topGrades = array_slice($positionStats, 0, 6, true);

                // Calculate totals
                $totalFilled = array_sum(array_column($positionStats, 'filled'));
                $totalVacant = array_sum(array_column($positionStats, 'vacant'));
                $totalPositions = count($allPositions);
            @endphp

            @foreach($topGrades as $grade => $stats)
                <div class="col-6 col-md-4 col-lg-2 mb-2">
                    <div class="grade-stat-card">
                        <div class="grade-header">
                            <div class="grade-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="grade-title">{{ $stats['title'] }}</div>
                        </div>
                        <div class="grade-total">{{ $stats['count'] }}</div>
                        <div class="grade-progress">
                            @php
                                $filledPercentage = $stats['count'] > 0 ?
                                    round(($stats['filled'] / ($stats['filled'] + $stats['vacant'])) * 100) : 0;
                            @endphp
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $filledPercentage }}%"></div>
                            </div>
                        </div>
                        <div class="grade-details">
                            <span class="filled">
                                <i class="fas fa-user-check"></i> {{ $stats['filled'] }}
                            </span>
                            <span class="vacant">
                                <i class="fas fa-user-times"></i> {{ $stats['vacant'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary Row -->
        <div class="summary-row mt-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-users text-primary"></i>
                            @if(app()->getLocale() == 'en')
                                Total Filled
                            @else
                                مجموع پر شده
                            @endif
                        </div>
                        <div class="summary-value text-success">{{ $totalFilled }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-user-slash text-warning"></i>
                            @if(app()->getLocale() == 'en')
                                Total Vacant
                            @else
                                مجموع خالی
                            @endif
                        </div>
                        <div class="summary-value text-danger">{{ $totalVacant }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-percentage text-info"></i>
                            @if(app()->getLocale() == 'en')
                                Fill Rate
                            @else
                                درصد پر شده
                            @endif
                        </div>
                        <div class="summary-value">
                            @php
                                $fillRate = ($totalFilled + $totalVacant) > 0 ?
                                    round(($totalFilled / ($totalFilled + $totalVacant)) * 100) : 0;
                            @endphp
                            {{ $fillRate }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Tree -->
    <div class="compact-tree-container {{ app()->getLocale() == 'en' ? 'ltr-tree' : 'rtl-tree' }}">
        <div class="compact-tree" id="orgTree">
            @php
                // Function to build tree properly
                $buildTree = function($parentId = null, $level = 0) use (&$buildTree, $allPositions) {
                    $children = $allPositions->where('parent_id', $parentId);

                    if($children->isEmpty()) return;

                    echo '<ul class="tree-level-' . $level . '">';

                    foreach($children as $position) {
                        $vacantPositions = $position->num_of_pos - $position->codes->count();
                        $filledCodes = $position->codes->where('employee', '!=', null)->count();
                        $emptyCodes = $position->codes->count() - $filledCodes;
                        $statusClass = $filledCodes >= $position->num_of_pos ? 'filled' : 'vacant';
                        $childCount = $allPositions->where('parent_id', $position->id)->count();
            @endphp
            <li class="tree-item" data-id="{{ $position->id }}" data-level="{{ $level }}">
                <div class="tree-connector">
                    @if($level > 0)
                        <!-- Vertical line from parent -->
                        <div class="vertical-line"></div>
                        <!-- Horizontal line to node -->
                        <div class="horizontal-line"></div>
                    @endif
                </div>

                <div class="tree-node level-{{ min($level, 4) }} {{ $statusClass }}">
                    <a href="{{ route('admin.office.positions.show', $position->id) }}"
                       class="node-link"
                       data-toggle="tooltip"
                       title="{{ $position->title }}&#013;درجه: {{ $position->position_number }}&#013;پر: {{ $filledCodes }} | خالی: {{ $emptyCodes + $vacantPositions }}">

                        <div class="node-icon">
                            @if($level == 0)
                                <i class="fas fa-flag"></i>
                            @elseif($level == 1)
                                <i class="fas fa-star"></i>
                            @elseif($level == 2)
                                <i class="fas fa-user-tie"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="node-content">
                            <div class="node-title">
                                {{ Str::limit($position->title, 18) }}
                            </div>
                            <div class="node-info">
                                <small class="grade-badge">
                                    {{ $position->position_number }} درجه
                                </small>
                                @if($childCount > 0)
                                    <span class="children-count">
                                                    <i class="fas fa-sitemap"></i> {{ $childCount }}
                                                </span>
                                @endif
                            </div>
                            <div class="node-status">
                                <span class="status-dot {{ $statusClass }}"></span>
                                <small>
                                    {{ $filledCodes }}/{{ $position->num_of_pos }}
                                </small>
                            </div>
                        </div>
                    </a>
                </div>

                @if($childCount > 0)
                    {{ $buildTree($position->id, $level + 1) }}
                @endif
            </li>
            @php
                }
                echo '</ul>';
            };
            @endphp

            {{ $buildTree(0) }}
        </div>
    </div>

    <!-- Legend -->
    <div class="chart-legend mt-3">
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <div class="legend-item">
                <span class="legend-color" style="background-color: #fff3cd;"></span>
                <span class="legend-text">سطح اول</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #d1ecf1;"></span>
                <span class="legend-text">سطح دوم</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: #d4edda;"></span>
                <span class="legend-text">سطح سوم</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot filled"></span>
                <span class="legend-text">پر</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot vacant"></span>
                <span class="legend-text">خالی</span>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Template -->
<div id="printTemplate" style="display: none;">
    <!-- This will be filled by JavaScript -->
</div>

<style>
    /* Compact Organization Chart */
    .compact-org-chart {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    /* Statistics Summary */
    .stats-summary {
        background: linear-gradient(135deg, #f8f9fc, #e9ecef);
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1rem;
    }

    .grade-stat-card {
        background: white;
        border-radius: 8px;
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s;
        text-align: center;
        height: 100%;
    }

    .grade-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }

    .grade-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
    }

    .grade-icon {
        width: 30px;
        height: 30px;
        background: linear-gradient(135deg, #4361ee, #6c5ce7);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-left: 0.5rem;
    }

    .grade-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .grade-total {
        font-size: 1.8rem;
        font-weight: bold;
        color: #2c3e50;
        margin: 0.5rem 0;
    }

    .grade-progress {
        margin: 0.5rem 0;
    }

    .grade-progress .progress {
        height: 6px;
        border-radius: 3px;
    }

    .grade-details {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
    }

    .grade-details .filled {
        color: #28a745;
    }

    .grade-details .vacant {
        color: #dc3545;
    }

    .grade-details i {
        margin-left: 0.25rem;
    }

    /* Summary Row */
    .summary-row {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e3e6f0;
    }

    .summary-item {
        text-align: center;
        padding: 0.5rem;
    }

    .summary-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .summary-label i {
        margin-left: 0.5rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Tree Container */
    .compact-tree-container {
        overflow: auto;
        max-height: 400px;
        background: #f8f9fc;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1rem 0;
        border: 1px solid #e3e6f0;
    }

    .rtl-tree {
        direction: rtl;
    }

    .ltr-tree {
        direction: ltr;
    }

    /* Tree Structure */
    .compact-tree {
        position: relative;
        min-width: 600px;
        margin: 0 auto;
        padding: 2rem 0;
    }

    .compact-tree ul {
        position: relative;
        padding: 0;
        margin: 0;
        text-align: center;
        padding-top: 2rem;
    }

    .tree-item {
        display: inline-block;
        text-align: center;
        list-style: none;
        position: relative;
        padding: 2rem 1rem 0 1rem;
        vertical-align: top;
    }

    /* Tree Connector Lines */
    .tree-connector {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: 2rem;
        z-index: 0;
    }

    .vertical-line {
        width: 2px;
        height: 2rem;
        background: #adb5bd;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .horizontal-line {
        width: 50%;
        height: 2px;
        background: #adb5bd;
        position: absolute;
        top: 2rem;
        left: 50%;
    }

    /* For first child */
    .tree-item:first-child .horizontal-line {
        left: 50%;
        width: 50%;
    }

    /* For last child */
    .tree-item:last-child .horizontal-line {
        left: 0;
        width: 50%;
    }

    /* For only child */
    .tree-item:only-child .horizontal-line {
        display: none;
    }

    /* For middle children */
    .tree-item:not(:first-child):not(:last-child) .horizontal-line {
        left: 0;
        width: 100%;
    }

    /* Tree Node */
    .tree-node {
        position: relative;
        background: white;
        border: 2px solid;
        border-radius: 8px;
        padding: 0.75rem;
        min-width: 160px;
        z-index: 1;
        transition: all 0.3s;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }

    .tree-node:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .tree-node.filled {
        border-color: #28a745;
    }

    .tree-node.vacant {
        border-color: #ffc107;
    }

    /* Level Colors */
    .tree-node.level-0 {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
        border-color: #ffc107 !important;
    }
    .tree-node.level-1 {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb) !important;
        border-color: #17a2b8 !important;
    }
    .tree-node.level-2 {
        background: linear-gradient(135deg, #d4edda, #c3e6cb) !important;
        border-color: #28a745 !important;
    }
    .tree-node.level-3 {
        background: linear-gradient(135deg, #cce5ff, #b3d7ff) !important;
        border-color: #007bff !important;
    }
    .tree-node.level-4 {
        background: linear-gradient(135deg, #e2e3e5, #d6d8db) !important;
        border-color: #6c757d !important;
    }

    /* Node Link */
    .node-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }

    .node-link:hover {
        text-decoration: none;
        color: inherit;
    }

    /* Node Icon */
    .node-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #4361ee, #6c5ce7);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        margin-left: 0.75rem;
        flex-shrink: 0;
    }

    .level-0 .node-icon {
        background: linear-gradient(135deg, #ff9f43, #ff922b);
    }

    .level-1 .node-icon {
        background: linear-gradient(135deg, #17a2b8, #3dc7be);
    }

    .level-2 .node-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .level-3 .node-icon {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    /* Node Content */
    .node-content {
        flex: 1;
        text-align: right;
        min-width: 0;
    }

    .ltr-tree .node-content {
        text-align: left;
    }

    .node-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
        margin-bottom: 0.25rem;
        white-space: normal;
    }

    .node-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .grade-badge {
        background: #f8f9fa;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        font-size: 0.7rem;
        color: #6c757d;
    }

    .children-count {
        background: #e9ecef;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        font-size: 0.7rem;
        color: #495057;
    }

    .children-count i {
        font-size: 0.6rem;
        margin-left: 0.25rem;
    }

    .node-status {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .ltr-tree .node-status {
        justify-content: flex-start;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-left: 0.5rem;
    }

    .status-dot.filled {
        background-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
    }

    .status-dot.vacant {
        background-color: #ffc107;
        box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
    }

    .node-status small {
        font-size: 0.75rem;
        color: #6c757d;
    }

    /* Print Styles */
    @page {
        size: landscape;
        margin: 15mm;
    }
    /* Add to the existing org_tab CSS */
    @media print {
        .compact-tree {
            transform: scale(0.8);
            transform-origin: top center;
        }

        .tree-node {
            min-width: 120px !important;
            padding: 0.5rem !important;
        }

        .node-title {
            font-size: 0.7rem !important;
        }

        .node-icon {
            width: 24px !important;
            height: 24px !important;
            font-size: 0.8rem !important;
        }

        .compact-tree-container {
            max-height: none !important;
            overflow: visible !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .compact-tree-container {
            max-height: 350px;
        }

        .compact-tree {
            min-width: 450px;
        }

        .tree-node {
            min-width: 140px;
            padding: 0.5rem;
        }

        .node-icon {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .grade-stat-card {
            padding: 0.5rem;
        }

        .grade-total {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .compact-tree {
            min-width: 350px;
            padding: 1rem 0;
        }

        .tree-item {
            padding: 1.5rem 0.5rem 0 0.5rem;
        }

        .tree-node {
            min-width: 120px;
        }

        .node-title {
            font-size: 0.75rem;
        }

        .grade-stat-card {
            padding: 0.4rem;
        }

        .grade-total {
            font-size: 1.2rem;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'top',
            boundary: 'window'
        });

        // Make tree draggable
        let isDragging = false;
        let startX, startY, scrollLeft, scrollTop;
        const container = $('.compact-tree-container');

        container.on('mousedown', function(e) {
            if ($(e.target).closest('.tree-node').length) return;

            isDragging = true;
            startX = e.pageX - container.offset().left;
            startY = e.pageY - container.offset().top;
            scrollLeft = container.scrollLeft();
            scrollTop = container.scrollTop();
            container.css('cursor', 'grabbing');
        });

        $(document).on('mousemove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - container.offset().left;
            const y = e.pageY - container.offset().top;
            const walkX = (x - startX) * 2;
            const walkY = (y - startY) * 2;
            container.scrollLeft(scrollLeft - walkX);
            container.scrollTop(scrollTop - walkY);
        });

        $(document).on('mouseup', function() {
            isDragging = false;
            container.css('cursor', 'grab');
        });

        // Initialize cursor
        container.css('cursor', 'grab');

        // Highlight path on hover
        $('.tree-node').on('mouseenter', function() {
            const node = $(this).closest('.tree-item');
            const level = node.data('level');

            // Highlight this node and its children
            node.addClass('active');
            node.find('.tree-item').addClass('active-child');

            // Highlight parent path
            node.parentsUntil('.compact-tree', '.tree-item').addClass('active-parent');
        }).on('mouseleave', function() {
            $('.active, .active-child, .active-parent').removeClass('active active-child active-parent');
        });
    });

    // Simple zoom function
    function chartZoom(scale) {
        const tree = $('#orgTree');
        const container = $('.compact-tree-container');

        if (scale === 1) {
            // Reset zoom and scroll
            tree.css('transform', 'scale(1)');
            container.scrollLeft(0);
            container.scrollTop(0);
        } else {
            const currentScale = parseFloat(tree.css('transform').split(',')[0]) || 1;
            const newScale = scale === 0.9 ? currentScale * 0.9 : currentScale * 1.1;

            // Limit zoom
            if (newScale >= 0.5 && newScale <= 2) {
                tree.css('transform', `scale(${newScale})`);
            }
        }
    }

    // Print chart function
    function printChart() {
        // Get current data
        const now = new Date();
        const persianDate = now.toLocaleDateString('fa-IR');
        const timeStr = now.toLocaleTimeString('fa-IR');
        const userName = $('meta[name="user-name"]').attr('content') || 'مدیر سیستم';

        // Get statistics from the page
        const totalPositions = $('#statsForPrint .badge').text().trim() || '0';

        // Extract filled and vacant counts
        let totalFilled = '0', totalVacant = '0';
        const summaryValues = $('#statsForPrint .summary-value');
        if (summaryValues.length >= 2) {
            totalFilled = summaryValues.first().text().trim();
            totalVacant = summaryValues.eq(1).text().trim();
        }

        // Create print content
        const printContent = `
        <div class="print-content">
            <!-- Header -->
            <div class="print-header">
                <div class="header-main">
                    <div class="header-logo">
                        <div class="logo-circle">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div class="logo-text">
                            <h1>چارت تشکیلاتی</h1>
                            <h2>آمریت گمرک سرحدی حیرتان</h2>
                        </div>
                    </div>
                    <div class="header-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>تاریخ: ${persianDate}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>ساعت: ${timeStr}</span>
                        </div>
                    </div>
                </div>

                <div class="header-stats">
                    <div class="stat-badge">
                        <i class="fas fa-layer-group"></i>
                        <div class="stat-content">
                            <span class="stat-label">کل بست‌ها</span>
                            <span class="stat-value">${totalPositions}</span>
                        </div>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-user-check"></i>
                        <div class="stat-content">
                            <span class="stat-label">بست‌های پر</span>
                            <span class="stat-value" style="color: #28a745;">${totalFilled}</span>
                        </div>
                    </div>
                    <div class="stat-badge">
                        <i class="fas fa-user-times"></i>
                        <div class="stat-content">
                            <span class="stat-label">بست‌های خالی</span>
                            <span class="stat-value" style="color: #dc3545;">${totalVacant}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="print-section">
                <h3 class="section-title">
                    <i class="fas fa-chart-pie"></i>
                    آمار بست‌ها
                </h3>
                <div class="stats-grid">
                    ${getStatisticsHTML()}
                </div>
            </div>

            <!-- Organization Chart -->
            <div class="print-section">
                <h3 class="section-title">
                    <i class="fas fa-sitemap"></i>
                    ساختار سازمانی
                </h3>

                <div class="org-chart-container">
                    ${getTreeHTML()}
                </div>

                <!-- Legend -->
                <div class="print-legend">
                    <div class="legend-title">راهنما:</div>
                    <div class="legend-items">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #fff3cd;"></span>
                            <span class="legend-text">سطح اول</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #d1ecf1;"></span>
                            <span class="legend-text">سطح دوم</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #d4edda;"></span>
                            <span class="legend-text">سطح سوم</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="print-footer">
                <div class="footer-content">
                    <div class="footer-text">
                        سیستم مدیریت گمرک بلخ - چاپ شده توسط مدیریت سیستم
                    </div>
                    <div class="footer-meta">
                        <span>کاربر: ${userName}</span>
                        <span class="mx-1">|</span>
                        <span>${persianDate} ${timeStr}</span>
                    </div>
                </div>
            </div>
        </div>
    `;

        // Create print window
        const printWindow = window.open('', '_blank', 'width=1100,height=750');

        printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl">
        <head>
            <title>گزارش ساختار سازمانی</title>
            <meta charset="utf-8">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                @page {
                    size: landscape;
                    margin: 3mm;
                }

                * {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }

                body {
                    font-family: Tahoma, Arial, sans-serif;
                    direction: rtl;
                    margin: 0;
                    padding: 5mm;
                    color: #000;
                    background: white;
                    font-size: 10px;
                    line-height: 1.2;
                    width: 100%;
                    height: 100%;
                }

                /* Print Content */
                .print-content {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                /* Header */
                .print-header {
                    border-bottom: 2px solid #4361ee;
                    padding-bottom: 10px;
                    margin-bottom: 10px;
                }

                .header-main {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 10px;
                }

                .header-logo {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .logo-circle {
                    width: 45px;
                    height: 45px;
                    background: #4361ee;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 18px;
                }

                .logo-text h1 {
                    margin: 0;
                    font-size: 16px;
                    color: #2c3e50;
                }

                .logo-text h2 {
                    margin: 2px 0 0 0;
                    font-size: 12px;
                    color: #6c757d;
                }

                .header-meta {
                    text-align: left;
                }

                .meta-item {
                    margin-bottom: 3px;
                    font-size: 10px;
                }

                .meta-item i {
                    margin-left: 5px;
                    color: #4361ee;
                }

                .header-stats {
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                }

                .stat-badge {
                    display: flex;
                    align-items: center;
                    background: #f8f9fc;
                    border-radius: 6px;
                    padding: 6px 10px;
                    border: 1px solid #e3e6f0;
                }

                .stat-badge i {
                    font-size: 16px;
                    color: #4361ee;
                    margin-left: 8px;
                }

                .stat-label {
                    font-size: 9px;
                    color: #6c757d;
                }

                .stat-value {
                    font-size: 14px;
                    font-weight: bold;
                }

                /* Sections */
                .print-section {
                    margin-bottom: 10px;
                }

                .section-title {
                    font-size: 12px;
                    color: #2c3e50;
                    border-bottom: 1px solid #e3e6f0;
                    padding-bottom: 5px;
                    margin-bottom: 8px;
                }

                .section-title i {
                    margin-left: 6px;
                    color: #4361ee;
                }

                /* Statistics Grid */
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 6px;
                    margin-bottom: 10px;
                }

                .stats-grid > div {
                    background: white;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    padding: 6px;
                    text-align: center;
                }

                .stats-grid .grade-title {
                    font-size: 9px;
                    color: #6c757d;
                    margin-bottom: 3px;
                }

                .stats-grid .grade-total {
                    font-size: 14px;
                    font-weight: bold;
                    margin: 3px 0;
                }

                .stats-grid .grade-details {
                    font-size: 8px;
                    display: flex;
                    justify-content: space-between;
                }

                /* Organization Chart */
                .org-chart-container {
                    background: #f8f9fc;
                    border-radius: 6px;
                    padding: 10px;
                    border: 1px solid #e3e6f0;
                    margin-bottom: 8px;
                    max-height: 280px;
                    overflow: hidden;
                }

                .org-tree {
                    text-align: center;
                    position: relative;
                    padding: 15px 0;
                }

                .org-tree ul {
                    position: relative;
                    padding: 15px 0 0 0;
                    margin: 0;
                    white-space: nowrap;
                }

                .org-tree li {
                    display: inline-table;
                    text-align: center;
                    list-style-type: none;
                    position: relative;
                    padding: 15px 3px 0 3px;
                    vertical-align: top;
                }

                /* Tree Lines */
                .org-tree li::before, .org-tree li::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    right: 50%;
                    width: 50%;
                    height: 15px;
                    border-top: 1px solid #666;
                }

                .org-tree li::after {
                    right: auto;
                    left: 50%;
                    border-left: 1px solid #666;
                }

                .org-tree li:only-child::before, .org-tree li:only-child::after {
                    display: none;
                }

                .org-tree li:only-child {
                    padding-top: 0;
                }

                .org-tree li:first-child::before, .org-tree li:last-child::after {
                    border: 0 none;
                }

                .org-tree li:last-child::before {
                    border-right: 1px solid #666;
                }

                .org-tree ul ul::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    right: 50%;
                    border-left: 1px solid #666;
                    width: 0;
                    height: 15px;
                }

                /* Position Nodes */
                .position-node {
                    position: relative;
                    display: inline-block;
                    width: 85px;
                    border: 1px solid;
                    border-radius: 4px;
                    padding: 4px;
                    background: white;
                    text-align: center;
                    z-index: 1;
                }

                .position-node.level-0 {
                    background-color: #fff3cd !important;
                    border-color: #ffc107 !important;
                }

                .position-node.level-1 {
                    background-color: #d1ecf1 !important;
                    border-color: #17a2b8 !important;
                }

                .position-node.level-2 {
                    background-color: #d4edda !important;
                    border-color: #28a745 !important;
                }

                .position-node.level-3 {
                    background-color: #e8f4ff !important;
                    border-color: #007bff !important;
                }

                .position-node.filled {
                    border-color: #28a745 !important;
                }

                .position-node.vacant {
                    border-color: #ffc107 !important;
                }

                .node-icon-print {
                    width: 18px;
                    height: 18px;
                    background: #4361ee;
                    border-radius: 3px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 9px;
                    margin: 0 auto 3px auto;
                }

                .level-0 .node-icon-print {
                    background: #ff9f43;
                }

                .level-1 .node-icon-print {
                    background: #17a2b8;
                }

                .level-2 .node-icon-print {
                    background: #28a745;
                }

                .node-title-print {
                    font-size: 8px;
                    font-weight: bold;
                    color: #2c3e50;
                    margin-bottom: 2px;
                    line-height: 1.1;
                    height: 16px;
                    overflow: hidden;
                }

                .node-info-print {
                    font-size: 7px;
                    color: #666;
                }

                /* Legend */
                .print-legend {
                    background: #f8f9fc;
                    border-radius: 5px;
                    padding: 6px;
                    border: 1px solid #e3e6f0;
                    font-size: 8px;
                }

                .legend-title {
                    font-weight: 600;
                    margin-bottom: 4px;
                }

                .legend-items {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .legend-item {
                    display: flex;
                    align-items: center;
                }

                .legend-color {
                    width: 8px;
                    height: 8px;
                    border-radius: 1px;
                    margin-left: 4px;
                    border: 1px solid #ccc;
                }

                /* Footer */
                .print-footer {
                    margin-top: 10px;
                    padding-top: 8px;
                    border-top: 1px solid #e3e6f0;
                    font-size: 9px;
                    color: #6c757d;
                }

                .footer-text {
                    margin-bottom: 3px;
                }

                .footer-meta {
                    font-size: 8px;
                }

                /* Print-specific styles */
                @media print {
                    body {
                        padding: 2mm !important;
                        zoom: 80% !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }

                    .org-chart-container {
                        max-height: 250px !important;
                    }

                    .org-tree {
                        transform: scale(0.85);
                        transform-origin: top center;
                    }

                    .position-node {
                        width: 75px !important;
                        padding: 3px !important;
                    }

                    .node-title-print {
                        font-size: 7px !important;
                    }
                }
            </style>
        </head>
        <body>
            ${printContent}
            <script>
                window.onload = function() {
                    // Force colors in print
                    const style = document.createElement('style');
                    style.innerHTML = \`
                        @media print {
                            * {
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                                color-adjust: exact !important;
                            }
                        }
                    \`;
                    document.head.appendChild(style);

                    setTimeout(function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 500);
                    }, 300);
                };
            <\/script>
        </body>
        </html>
    `);

        printWindow.document.close();
    }

    // Function to get statistics HTML
    function getStatisticsHTML() {
        let html = '';
        const gradeCards = $('#statsForPrint .grade-stat-card');

        if (gradeCards.length > 0) {
            gradeCards.each(function() {
                const $card = $(this);
                const title = $card.find('.grade-title').text() || '';
                const total = $card.find('.grade-total').text() || '0';
                const filled = $card.find('.filled').text().replace(/[^0-9]/g, '') || '0';
                const vacant = $card.find('.vacant').text().replace(/[^0-9]/g, '') || '0';

                html += `
                <div>
                    <div class="grade-title">${title}</div>
                    <div class="grade-total">${total}</div>
                    <div class="grade-details">
                        <span style="color: #28a745;">
                            <i class="fas fa-user-check" style="margin-left: 3px;"></i>
                            ${filled}
                        </span>
                        <span style="color: #dc3545;">
                            <i class="fas fa-user-times" style="margin-left: 3px;"></i>
                            ${vacant}
                        </span>
                    </div>
                </div>
            `;
            });
        } else {
            // Fallback if no cards found
            for (let i = 1; i <= 6; i++) {
                html += `
                <div>
                    <div class="grade-title">بست ${i}</div>
                    <div class="grade-total">0</div>
                    <div class="grade-details">
                        <span style="color: #28a745;">0</span>
                        <span style="color: #dc3545;">0</span>
                    </div>
                </div>
            `;
            }
        }

        return html;
    }

    // Function to get tree HTML
    function getTreeHTML() {
        // Try to get tree from the page first
        const $tree = $('#orgTree');
        if ($tree.length > 0) {
            const treeClone = $tree.clone();

            // Process the tree for printing
            treeClone.find('.tree-node').each(function() {
                const $node = $(this);
                const title = $node.find('.node-title').text() || 'بدون عنوان';
                const grade = $node.find('.grade-badge').text() || '';
                const levelClass = $node.attr('class').match(/level-\d+/)?.[0] || 'level-0';
                const statusClass = $node.hasClass('filled') ? 'filled' : 'vacant';
                const childCount = $node.closest('li').find('> ul > li').length;

                // Create simplified node
                const icon = levelClass === 'level-0' ? 'fa-flag' :
                    levelClass === 'level-1' ? 'fa-star' : 'fa-user';

                $node.replaceWith(`
                <div class="position-node ${levelClass} ${statusClass}">
                    <div class="node-icon-print">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="node-title-print">${title.substring(0, 15)}${title.length > 15 ? '...' : ''}</div>
                    <div class="node-info-print">
                        ${grade}
                        ${childCount > 0 ? ` (+${childCount})` : ''}
                    </div>
                </div>
            `);
            });

            // Remove links and tooltips
            treeClone.find('a').contents().unwrap();
            treeClone.find('[data-toggle="tooltip"]').removeAttr('data-toggle title');

            // Add org-tree class
            treeClone.addClass('org-tree');

            return treeClone[0].outerHTML;
        }

        // Fallback: Create a simple tree structure
        return `
        <div class="org-tree">
            <ul>
                <li>
                    <div class="position-node level-0">
                        <div class="node-icon-print">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="node-title-print">آمریت گمرک</div>
                        <div class="node-info-print">ریاست</div>
                    </div>
                    <ul>
                        <li>
                            <div class="position-node level-1">
                                <div class="node-icon-print">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="node-title-print">مدیریت ترانزیت</div>
                                <div class="node-info-print">درجه 2</div>
                            </div>
                        </li>
                        <li>
                            <div class="position-node level-1">
                                <div class="node-icon-print">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="node-title-print">مدیریت اداری</div>
                                <div class="node-info-print">درجه 2</div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    `;
    }
</script>
