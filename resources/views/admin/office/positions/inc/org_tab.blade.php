{{-- resources/views/admin/office/positions/inc/org_tab.blade.php --}}
@php
    // Use pre-calculated data from controller
    $tree = $organizationData['tree'] ?? [];
    $rootPosition = $organizationData['rootPosition'] ?? null;
    $positionStats = $organizationData['positionStats'] ?? [];
    $totalPositions = $organizationData['totalPositions'] ?? 0;
    $totalFilled = $organizationData['totalFilled'] ?? 0;
    $totalVacant = $organizationData['totalVacant'] ?? 0;
    $fillRate = $organizationData['fillRate'] ?? 0;
    $items = $organizationData['items'] ?? [];
@endphp

<div class="pdf-exact-org-chart" id="pdfExactOrgChart" style="font-family: Calibri, sans-serif;">
    <!-- Official Header -->
    <div class="official-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <div class="logo-container">
                    <div class="logo-circle rounded-circle">
                        <img class="rounded-circle" src="{{ asset('assets/images/emirate-logo.jpg') }}" alt="">
                    </div>
                    <div class="logo-text">
                        <h5 class="mb-0">امارت اسلامی افغانستان</h5>
                        <small class="text-muted">ریاست عمومی گمرکات</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3 class="mb-2 text-primary">چارت تشکیلی آمریت گمرک سرحدی حیرتان</h3>
                <div class="header-border"></div>
                <p class="mb-0 text-muted">ساختار سازمانی رسمی بر اساس احکام و مقررات</p>
            </div>
            <div class="col-md-3 text-center">
                <div class="document-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>تاریخ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-hashtag"></i>
                        <span>شماره: {{ rand(1000, 9999) }}/{{ \Morilog\Jalali\Jalalian::now()->format('Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Controls -->
    <div class="chart-controls-bar mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="controls-left">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="exactChartZoom(0.9)" title="کوچک‌نمایی">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="exactChartZoom(1)" title="اندازه اصلی">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="exactChartZoom(1.1)" title="بزرگ‌نمایی">
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
                <span class="zoom-level mx-2" id="zoomLevel">100%</span>
            </div>
            <div class="controls-right">
                <button class="btn btn-success btn-sm" onclick="printExactChart()">
                    <i class="fas fa-print me-1"></i>
                    چاپ چارت در یک صفحه
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (PDF Style) -->
    <div class="pdf-stats-table mb-4" id="pdfStatsTable" style="font-family: Calibri, sans-serif;">
        <div class="table-title mb-2">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie me-2"></i>
                آمار بست‌های مورد نیاز آمریت گمرک سرحدی حیرتان
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center">
                <thead class="table-light">
                <tr>
                    <th rowspan="2">موقف بست</th>
                    <th colspan="2">بست دوم</th>
                    <th colspan="2">بست سوم</th>
                    <th colspan="2">بست چهارم</th>
                    <th colspan="2">بست پنجم</th>
                    <th colspan="2">بست ششم</th>
                    <th colspan="2">بست هفتم</th>
                    <th colspan="2">بست هشتم</th>
                    <th rowspan="2">مجموع</th>
                </tr>
                <tr>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                    <th>تعیین</th><th>موجود</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="fw-bold">تعداد بست</td>
                    @php
                        $totalDetermined = 0;
                        $totalExisting = 0;
                    @endphp

                    @for($i = 2; $i <= 8; $i++)
                        @php
                            $determined = $positionStats[$i]['count'] ?? 0;
                            $existing = $positionStats[$i]['filled'] ?? 0;
                            $totalDetermined += $determined;
                            $totalExisting += $existing;
                        @endphp
                        <td>{{ $determined }}</td>
                        <td class="{{ $existing > 0 ? 'text-success fw-bold' : '' }}">{{ $existing }}</td>
                    @endfor
                    <td class="fw-bold table-primary">{{ $totalDetermined }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary Row -->
        <div class="summary-row mt-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">کل بست‌ها</div>
                            <div class="summary-value">{{ $totalPositions }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #28a745;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">بست‌های پر</div>
                            <div class="summary-value text-success">{{ $totalFilled }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #ffc107;">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">بست‌های خالی</div>
                            <div class="summary-value text-warning">{{ $totalVacant }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-card">
                        <div class="summary-icon" style="background: #17a2b8;">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-label">درصد پر شدن</div>
                            <div class="summary-value text-info">{{ $fillRate }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Chart with Tree Lines -->
    <div class="org-chart-container-wrapper">
        <div class="org-chart-container" id="orgChartContainer">
            <div class="org-chart-tree" id="orgChartTree">
                @if($rootPosition)
                    <!-- Render the organization tree starting from root -->
                    <div class="organization-tree">
                        @include('admin.office.positions.inc.org_tree', [
                            'position' => $rootPosition,
                            'level' => 0,
                            'items' => $items
                        ])
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        هیچ پست سازمانی تعریف نشده است. لطفاً ابتدا ساختار سازمانی را تعریف کنید.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="chart-legend mt-4">
        <div class="legend-title">
            <i class="fas fa-key me-2"></i>
            راهنمای نمادها
        </div>
        <div class="legend-items">
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, #fff3cd, #ffeaa7);"></div>
                <span class="legend-text">مدیریت ارشد (آمر)</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, #d1ecf1, #bee5eb);"></div>
                <span class="legend-text">مدیریت میانی</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);"></div>
                <span class="legend-text">کارشناسان</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: linear-gradient(135deg, #e2e3e5, #d6d8db);"></div>
                <span class="legend-text">کارکنان</span>
            </div>
            <div class="legend-item">
                <span class="status-dot filled"></span>
                <span class="legend-text">پست پر شده</span>
            </div>
            <div class="legend-item">
                <span class="status-dot vacant"></span>
                <span class="legend-text">پست خالی</span>
            </div>
        </div>
    </div>

    <!-- Official Footer -->
    <div class="official-footer mt-4 pt-3 border-top">
        <div class="row">
            <div class="col-md-6">
                <div class="footer-note">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    این چارت تشکیلی مطابق با احکام و مقررات ریاست عمومی گمرکات تنظیم شده است.
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="footer-meta">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt me-1"></i>
                        آخرین به‌روزرسانی: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}
                        |
                        <i class="fas fa-user me-1"></i>
                        تهیه کننده: سیستم مدیریت امور کارکنان
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- استایل‌ها و جاوااسکریپت بدون تغییر باقی می‌مانند --}}

<style>
    /* Main Container */
    .pdf-exact-org-chart {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        font-family: 'Tahoma', 'Arial', sans-serif;
    }

    /* Official Header */
    .official-header {
        background: linear-gradient(135deg, #f8f9fc, #e9ecef);
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }

    .logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .logo-circle {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 10px;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }

    .logo-text h5 {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .header-border {
        width: 200px;
        height: 3px;
        background: linear-gradient(to right, #4361ee, #3a56d4, #4361ee);
        margin: 10px auto;
        border-radius: 2px;
    }

    .document-meta .meta-item {
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .document-meta i {
        color: #4361ee;
        margin-left: 8px;
        width: 20px;
    }

    /* Chart Controls */
    .chart-controls-bar {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .zoom-level {
        font-family: monospace;
        font-weight: bold;
        color: #4361ee;
    }

    /* Statistics Table */
    .pdf-stats-table {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .table-title {
        border-bottom: 2px solid #4361ee;
        padding-bottom: 0.75rem;
        margin-bottom: 1rem;
    }

    .pdf-stats-table table {
        font-size: 0.85rem;
    }

    .pdf-stats-table th {
        background: #f8f9fa !important;
        font-weight: 600;
        vertical-align: middle;
        padding: 0.5rem;
    }

    .pdf-stats-table td {
        padding: 0.5rem;
        vertical-align: middle;
    }

    /* Summary Cards */
    .summary-row {
        margin-top: 1.5rem;
    }

    .summary-card {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1rem;
        height: 100%;
        transition: all 0.3s;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4361ee, #6c5ce7);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-left: 1rem;
        flex-shrink: 0;
    }

    .summary-content {
        flex: 1;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2c3e50;
    }

    /* Organization Chart Container */
    .org-chart-container-wrapper {
        position: relative;
        margin: 1.5rem 0;
    }

    .org-chart-container {
        overflow: auto;
        max-height: 600px;
        background: #f8f9fc;
        border-radius: 8px;
        padding: 2rem;
        border: 1px solid #e3e6f0;
        transform-origin: top center;
        transition: transform 0.3s ease;
    }

    .org-chart-tree {
        min-width: 1000px;
        min-height: 500px;
        position: relative;
        padding: 2rem 0;
    }

    /* Tree Levels */
    .tree-level {
        display: flex;
        justify-content: center;
        position: relative;
        margin-bottom: 3rem;
    }

    .tree-level.level-0 {
        margin-bottom: 4rem;
    }

    .tree-level.level-1 {
        margin-bottom: 3rem;
    }

    .tree-level.level-2 {
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .tree-level.level-3 {
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    /* Position Nodes */
    .position-node {
        position: relative;
        background: white;
        border: 2px solid;
        border-radius: 8px;
        padding: 1rem;
        min-width: 200px;
        z-index: 2;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }

    .position-node:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        z-index: 3;
    }

    /* Node Types */
    .root-node {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7) !important;
        border-color: #ffc107 !important;
        min-width: 300px;
        padding: 1.5rem 2rem;
    }

    .manager-node {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb) !important;
        border-color: #17a2b8 !important;
        margin: 0 1.5rem;
    }

    .staff-node {
        background: linear-gradient(135deg, #d4edda, #c3e6cb) !important;
        border-color: #28a745 !important;
    }

    .sub-staff-node {
        background: linear-gradient(135deg, #e2e3e5, #d6d8db) !important;
        border-color: #6c757d !important;
        min-width: 180px;
    }

    /* Node Header */
    .node-header {
        display: flex;
        align-items: center;
    }

    .node-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
        margin-left: 1rem;
        flex-shrink: 0;
    }

    .root-node .node-icon {
        background: linear-gradient(135deg, #ff9f43, #ff922b);
        width: 55px;
        height: 55px;
        font-size: 1.5rem;
    }

    .manager-node .node-icon {
        background: linear-gradient(135deg, #17a2b8, #3dc7be);
    }

    .staff-node .node-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .sub-staff-node .node-icon {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .node-content {
        flex: 1;
        text-align: right;
    }

    .node-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .root-node .node-title {
        font-size: 1.1rem;
    }

    .node-grade {
        font-size: 0.8rem;
        color: #6c757d;
        background: rgba(255,255,255,0.7);
        padding: 0.1rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .node-status {
        font-size: 0.8rem;
        font-weight: bold;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }

    .node-status.filled {
        background: rgba(40, 167, 69, 0.15);
        color: #28a745;
    }

    .node-status.vacant {
        background: rgba(255, 193, 7, 0.15);
        color: #0a3622;
    }

    /* Tree Lines */
    .tree-line {
        position: absolute;
        background: #adb5bd;
        z-index: 1;
    }

    .root-line {
        height: 40px;
        width: 2px;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
    }

    .parent-line {
        width: 2px;
        height: 30px;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .child-line {
        width: 2px;
        height: 30px;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Horizontal lines for level 1 */
    .tree-level.level-1 .position-node::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        background: #adb5bd;
        top: -30px;
        left: 0;
    }

    /* Legend */
    .chart-legend {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .legend-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .legend-items {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot.filled {
        background-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2);
    }

    .status-dot.vacant {
        background-color: #ffc107;
        box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
    }

    /* Official Footer */
    .official-footer {
        background: #f8f9fc;
        border-radius: 8px;
        padding: 1rem 1.5rem;
    }

    /* Print Styles */
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
        }

        body * {
            visibility: hidden;
        }

        .pdf-exact-org-chart,
        .pdf-exact-org-chart * {
            visibility: visible;
        }

        .pdf-exact-org-chart {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
            box-shadow: none;
            background: white;
        }

        .chart-controls-bar,
        .btn-group,
        button {
            display: none !important;
        }

        .org-chart-container {
            max-height: none !important;
            overflow: visible !important;
            padding: 1rem !important;
            border: none !important;
            background: none !important;
            transform: scale(0.85) !important;
            transform-origin: top center !important;
        }

        .org-chart-tree {
            min-width: 100% !important;
            padding: 0 !important;
        }

        .position-node {
            page-break-inside: avoid;
        }

        .root-node {
            min-width: 250px !important;
            padding: 1rem 1.5rem !important;
        }

        .manager-node {
            min-width: 180px !important;
        }

        /* Force colors in print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .org-chart-tree {
            min-width: 800px;
        }
    }

    @media (max-width: 768px) {
        .official-header .row {
            flex-direction: column;
            gap: 1rem;
        }

        .org-chart-container {
            padding: 1rem;
        }

        .org-chart-tree {
            min-width: 600px;
        }

        .tree-level {
            flex-direction: column;
            align-items: center;
        }

        .tree-level.level-1 .position-node {
            margin: 1rem 0;
        }
    }

    @media (max-width: 576px) {
        .org-chart-tree {
            min-width: 500px;
        }

        .summary-card {
            margin-bottom: 1rem;
        }

        .legend-items {
            gap: 1rem;
        }
    }
</style>

<script>
    // Define global variables
    let currentZoom = 1;
    let isChartDragging = false;
    let chartStartX, chartStartY, chartScrollLeft, chartScrollTop;

    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'top',
            boundary: 'window'
        });

        // Make chart container draggable
        const chartContainer = $('#orgChartContainer');
        const chartTree = $('#orgChartTree');

        chartContainer.on('mousedown', function(e) {
            if ($(e.target).closest('.position-node').length) return;

            isChartDragging = true;
            chartStartX = e.pageX - chartContainer.offset().left;
            chartStartY = e.pageY - chartContainer.offset().top;
            chartScrollLeft = chartContainer.scrollLeft();
            chartScrollTop = chartContainer.scrollTop();
            chartContainer.css('cursor', 'grabbing');

            // Prevent text selection while dragging
            e.preventDefault();
        });

        $(document).on('mousemove', function(e) {
            if (!isChartDragging) return;

            const x = e.pageX - chartContainer.offset().left;
            const y = e.pageY - chartContainer.offset().top;
            const walkX = (x - chartStartX) * 2;
            const walkY = (y - chartStartY) * 2;

            chartContainer.scrollLeft(chartScrollLeft - walkX);
            chartContainer.scrollTop(chartScrollTop - walkY);
        });

        $(document).on('mouseup', function() {
            isChartDragging = false;
            chartContainer.css('cursor', 'grab');
        });

        // Set initial cursor
        chartContainer.css('cursor', 'grab');

        // Highlight connections on hover
        $('.position-node').on('mouseenter', function() {
            const $node = $(this);
            $node.addClass('highlight');

            // Highlight parent line
            $node.find('.parent-line').addClass('highlight-line');

            // Highlight child line
            $node.find('.child-line').addClass('highlight-line');
        }).on('mouseleave', function() {
            $('.position-node').removeClass('highlight');
            $('.tree-line').removeClass('highlight-line');
        });

        // Initialize highlight styles
        if (!$('#highlight-styles').length) {
            $('head').append(`
                <style id="highlight-styles">
                    .position-node.highlight {
                        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.3) !important;
                        transform: translateY(-2px);
                    }
                    .tree-line.highlight-line {
                        background: #4361ee !important;
                        box-shadow: 0 0 5px rgba(67, 97, 238, 0.5);
                        z-index: 3;
                    }
                </style>
            `);
        }
    });

    // Chart Zoom Functions
    function exactChartZoom(scale) {
        const tree = $('#orgChartTree');
        const zoomLevel = $('#zoomLevel');

        if (scale === 1) {
            // Reset zoom and scroll
            currentZoom = 1;
            tree.css('transform', 'scale(1)');
            $('#orgChartContainer').scrollLeft(0).scrollTop(0);
            zoomLevel.text('100%');
        } else {
            const newZoom = scale === 0.9 ? currentZoom * 0.9 : currentZoom * 1.1;

            // Limit zoom between 0.5 and 2
            if (newZoom >= 0.5 && newZoom <= 2) {
                currentZoom = newZoom;
                tree.css('transform', `scale(${currentZoom})`);
                zoomLevel.text(`${Math.round(currentZoom * 100)}%`);
            }
        }

        // Update cursor for dragging
        const chartContainer = $('#orgChartContainer');
        chartContainer.css('cursor', currentZoom > 1 ? 'move' : 'grab');
    }

    // Print Chart Function
    function printExactChart() {
        // Store original states
        const originalTransform = $('#orgChartTree').css('transform');
        const originalContainerStyle = $('#orgChartContainer').attr('style') || '';
        const originalTreeStyle = $('#orgChartTree').attr('style') || '';

        // Reset zoom for printing
        if (currentZoom !== 1) {
            exactChartZoom(1);
        }

        // Add print styles
        const printStyle = document.createElement('style');
        printStyle.innerHTML = `
            @media print {
                @page {
                    size: landscape;
                    margin: 10mm;
                }
                body * {
                    visibility: hidden;
                }
                .pdf-exact-org-chart,
                .pdf-exact-org-chart * {
                    visibility: visible;
                }
                .pdf-exact-org-chart {
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 100% !important;
                    padding: 5mm !important;
                    margin: 0 !important;
                    box-shadow: none !important;
                    background: white !important;
                }
                .chart-controls-bar,
                .btn-group,
                button,
                [onclick] {
                    display: none !important;
                }
                .org-chart-container {
                    max-height: none !important;
                    overflow: visible !important;
                    padding: 2mm !important;
                    border: none !important;
                    background: none !important;
                    transform: none !important;
                }
                .org-chart-tree {
                    transform: scale(0.85) !important;
                    transform-origin: top center !important;
                    min-width: 100% !important;
                }
                .position-node {
                    page-break-inside: avoid !important;
                }
                .root-node {
                    min-width: 250px !important;
                    padding: 8px 12px !important;
                }
                .manager-node {
                    min-width: 160px !important;
                    padding: 6px 10px !important;
                }
                /* Force colors in print */
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
            }
        `;

        document.head.appendChild(printStyle);

        // Create a print-friendly version
        const printContent = $('#pdfExactOrgChart').clone();

        // Remove interactive elements
        printContent.find('.chart-controls-bar, .btn-group, button').remove();

        // Create print window
        const printWindow = window.open('', '_blank', 'width=1200,height=800');

        // Get current date and time
        const now = new Date();
        const persianDate = now.toLocaleDateString('fa-IR');
        const timeStr = now.toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });

        printWindow.document.write(`
            <!DOCTYPE html>
            <html dir="rtl" lang="fa">
            <head>
                <title>چارت تشکیلاتی - آمریت گمرک سرحدی حیرتان</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Tahoma', 'Arial', sans-serif;
                        direction: rtl;
                        margin: 0;
                        padding: 0;
                        background: white;
                    }
                    @page {
                        size: landscape;
                        margin: 10mm;
                    }
                    .print-container {
                        padding: 5mm;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 15px;
                        padding-bottom: 10px;
                        border-bottom: 2px solid #4361ee;
                    }
                    .print-header h1 {
                        font-size: 20px;
                        color: #2c3e50;
                        margin-bottom: 5px;
                    }
                    .print-header h2 {
                        font-size: 14px;
                        color: #6c757d;
                        margin-bottom: 10px;
                    }
                    .print-meta {
                        display: flex;
                        justify-content: space-between;
                        font-size: 11px;
                        color: #6c757d;
                        margin-bottom: 15px;
                    }
                    /* Ensure colors print correctly */
                    * {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <div class="print-header">
                        <h1><i class="fas fa-sitemap me-2"></i>چارت تشکیلاتی رسمی</h1>
                        <h2>آمریت گمرک سرحدی حیرتان - ریاست عمومی گمرکات</h2>
                        <div class="print-meta">
                            <span><i class="fas fa-calendar-alt me-1"></i>تاریخ چاپ: ${persianDate}</span>
                            <span><i class="fas fa-clock me-1"></i>ساعت: ${timeStr}</span>
                            <span><i class="fas fa-file-alt me-1"></i>سند رسمی</span>
                        </div>
                    </div>
                    ${printContent[0].outerHTML}
                </div>
                <script>
                    window.onload = function() {
                        // Small delay to ensure everything is loaded
                        setTimeout(function() {
                            window.print();
                            // Close window after printing
                            setTimeout(function() {
                                window.close();
                            }, 1000);
                        }, 500);
                    };
                <\/script>
            </body>
            </html>
        `);

        printWindow.document.close();

        // Clean up after printing
        printWindow.addEventListener('afterprint', function() {
            // Remove print styles
            document.head.removeChild(printStyle);

            // Restore original styles
            $('#orgChartTree').css('transform', originalTransform);
            $('#orgChartContainer').attr('style', originalContainerStyle);
            $('#orgChartTree').attr('style', originalTreeStyle);

            // Update zoom display
            $('#zoomLevel').text(Math.round(currentZoom * 100) + '%');

            // Close print window
            setTimeout(() => {
                if (printWindow && !printWindow.closed) {
                    printWindow.close();
                }
            }, 500);
        });
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Prevent default only for our shortcuts
        if (e.ctrlKey) {
            switch(e.key) {
                case 'p':
                    e.preventDefault();
                    printExactChart();
                    break;
                case '+':
                case '=':
                    e.preventDefault();
                    exactChartZoom(1.1);
                    break;
                case '-':
                case '_':
                    e.preventDefault();
                    exactChartZoom(0.9);
                    break;
                case '0':
                case ')':
                    e.preventDefault();
                    exactChartZoom(1);
                    break;
            }
        }
    });
    $(document).ready(function() {
        $('#zoomLevel').text('100%');
    });
</script>
