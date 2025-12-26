<!-- Export Modal -->
<div class="modal fade export-modal" id="exportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-export mr-2 text-primary"></i>خروجی گرفتن از لیست بست‌ها
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Format Selection -->
                <div class="mb-4">
                    <h6 class="mb-3">انتخاب فرمت:</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="export-option active" data-format="pdf">
                                <div class="d-flex align-items-center">
                                    <div class="export-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">PDF</div>
                                        <small class="text-muted">با کیفیت بالا و قابلیت چاپ</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="export-option" data-format="excel">
                                <div class="d-flex align-items-center">
                                    <div class="export-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">Excel</div>
                                        <small class="text-muted">برای ویرایش و آنالیز</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="export-option" data-format="csv">
                                <div class="d-flex align-items-center">
                                    <div class="export-icon" style="background: linear-gradient(135deg, #ffc107, #ff922b);">
                                        <i class="fas fa-file-csv"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">CSV</div>
                                        <small class="text-muted">ساده و سازگار</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="export-option" data-format="print">
                                <div class="d-flex align-items-center">
                                    <div class="export-icon" style="background: linear-gradient(135deg, #6c757d, #495057);">
                                        <i class="fas fa-print"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">چاپ مستقیم</div>
                                        <small class="text-muted">چاپ بدون ذخیره</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="export-filters">
                    <h6 class="mb-3">فیلترهای اختیاری:</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">وضعیت:</label>
                            <select class="form-control form-control-sm" id="exportStatusFilter">
                                <option value="all">همه</option>
                                <option value="filled">پر شده</option>
                                <option value="empty">خالی</option>
                                <option value="partial">نیمه پر</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">سطح:</label>
                            <select class="form-control form-control-sm" id="exportLevelFilter">
                                <option value="all">همه سطوح</option>
                                <option value="1">سطح ۱ (ریاست)</option>
                                <option value="2">سطح ۲ (مدیریت)</option>
                                <option value="3">سطح ۳ (عملیاتی)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="exportWithDetails" checked>
                        <label class="form-check-label" for="exportWithDetails">شامل جزئیات کامل</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exportOnlyVisible" checked>
                        <label class="form-check-label" for="exportOnlyVisible">فیلترهای جاری اعمال شود</label>
                    </div>
                </div>

                <!-- Language Selection -->
                <div class="mt-4">
                    <h6 class="mb-3">زبان گزارش:</h6>
                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                        <label class="btn btn-outline-primary active">
                            <input type="radio" name="exportLang" value="fa" checked> فارسی
                        </label>
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="exportLang" value="ps"> پشتو
                        </label>
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="exportLang" value="en"> انگلیسی
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="exportNowBtn">
                    <i class="fas fa-download mr-1"></i>دانلود خروجی
                </button>
            </div>
        </div>
    </div>
</div>
