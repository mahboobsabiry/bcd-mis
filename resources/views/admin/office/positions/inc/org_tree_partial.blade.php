{{-- admin/office/positions/inc/org_tree_partial.blade.php --}}
@php
    // Helper function to get children of a position
    function getChildren($items, $parentId) {
        $children = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $children[] = $item;
            }
        }
        return $children;
    }
@endphp

@if($position)
    <!-- Level {{ $level }} -->
    <div class="tree-level level-{{ $level }}">
        <div class="position-node {{ $level == 0 ? 'root-node' : ($level == 1 ? 'manager-node' : ($level == 2 ? 'staff-node' : 'sub-staff-node')) }} {{ $position['status'] }}">
            <!-- Tree Line to Parent -->
            @if($level > 0)
                <div class="tree-line parent-line"></div>
            @endif

            <div class="node-header">
                <div class="node-icon">
                    @if($level == 0)
                        <i class="fas fa-flag"></i>
                    @elseif($level == 1)
                        <i class="fas fa-user-tie"></i>
                    @elseif($level == 2)
                        <i class="fas fa-user"></i>
                    @else
                        <i class="fas fa-user-friends"></i>
                    @endif
                </div>
                <div class="node-content">
                    <div class="node-title">{{ $position['title'] }}</div>
                    <div class="node-grade">بست {{ $position['position_number'] }}</div>
                    <div class="node-status {{ $position['status'] }}">
                        {{ $position['filled_codes_count'] }}/{{ $position['num_of_pos'] }}
                    </div>
                </div>
            </div>

            <!-- Get children -->
            @php
                $children = getChildren($items, $position['id']);
            @endphp

                <!-- Tree Line to Children -->
            @if(!empty($children))
                <div class="tree-line child-line"></div>

                <!-- Render children -->
                <div class="tree-level level-{{ $level + 1 }}">
                    @foreach($children as $child)
                        @include('admin.office.positions.inc.org_tree_partial', [
                            'position' => $child,
                            'level' => $level + 1,
                            'items' => $items
                        ])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
