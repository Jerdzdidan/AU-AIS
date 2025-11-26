class GenericDataTable {
    constructor(config) {
        this.tableId = config.tableId;
        this.ajaxUrl = config.ajaxUrl;
        this.columns = config.columns;
        this.statsCards = config.statsCards || null;
        this.table = null;
    }
    
    init() {
        this.table = $(`#${this.tableId}`).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: this.ajaxUrl,
                type: "GET",
                error: (xhr, error) => {
                    console.error('Error:', error);
                    toastr.error('Error loading data. Please refresh.');
                }
            },
            columns: this.columns,
            order: [[1, "asc"]],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                processing: '<div class="spinner-border text-primary"></div> Loading...',
                emptyTable: "No records available"
            },
            drawCallback: () => {
                if (this.statsCards) this.updateStats();
            }
        });
        
        return this;
    }
    
    updateStats() {
        const info = this.table.page.info();
        if (this.statsCards.total) {
            $(`#${this.statsCards.total}`).text(info.recordsTotal);
        }
        
        // Custom stats logic can be passed
        if (this.statsCards.callback) {
            this.statsCards.callback(this.table);
        }
    }
    
    reload() {
        this.table.ajax.reload(null, false);
    }
}