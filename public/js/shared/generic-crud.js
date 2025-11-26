class GenericCRUD {
    constructor(config) {
        this.baseUrl = config.baseUrl;
        this.storeUrl = config.storeUrl;
        this.destroyUrl = config.destroyUrl;
        this.entityName = config.entityName;
        this.dataTable = config.dataTable;
        this.csrfToken = config.csrfToken;
        this.modal = config.modal;
        this.form = config.form;
    }
    
    view(id) {
        $.ajax({
            url: `${this.baseUrl}/${id}`,
            method: 'GET',
            success: (response) => {
                // Trigger custom callback if provided
                if (this.onViewSuccess) this.onViewSuccess(response);
            },
            error: () => toastr.error(`Failed to load ${this.entityName}`)
        });
    }
    
    edit(id) {
        $.ajax({
            url: `${this.baseUrl}/edit/${id}`,
            method: 'GET',
            success: (response) => {
                if (this.onEditSuccess) this.onEditSuccess(response);
            },
            error: () => toastr.error(`Failed to load ${this.entityName}`)
        });
    }
    
    create(formData) {
        $.ajax({
            url: `${this.storeUrl}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': this.csrfToken },
            success: (response) => {
                toastr.success(`${this.entityName} created successfully`);
                if (this.onCreateSuccess) this.onCreateSuccess(response);
                $(this.modal).offcanvas('hide');
                $(this.form)[0].reset();
                this.dataTable.reload();
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    let errors = {};
                    try {
                        const json = xhr.responseJSON ?? JSON.parse(xhr.responseText);
                        errors = json?.errors ?? {};
                    } catch (e) {
                        console.error('Could not parse error JSON', e);
                    }

                    const allMessages = Object.values(errors)     
                        .flat()                              
                        .map(msg => `<li>${msg}</li>`) 
                        .join('');  

                    const htmlMessage = `<ul style="margin:0; padding-left:20px;">${allMessages}</ul>`;

                    if (htmlMessage) {
                        toastr.error(htmlMessage, 'Validation Error:', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000,
                            extendedTimeOut: 2000,
                            escapeHtml: false
                        });
                    } else {
                        toastr.error('Please check your input.', 'Validation Error');
                    }
                    return;
                }

                toastr.error(`Failed to create ${this.entityName}`);
            }
        });
    }
    
    update(id, formData) {
        $.ajax({
            url: `${this.baseUrl}/${id}`,
            method: 'PUT',
            data: formData,
            headers: { 'X-CSRF-TOKEN': this.csrfToken },
            success: (response) => {
                toastr.success(`${this.entityName} updated successfully`);
                if (this.onUpdateSuccess) this.onUpdateSuccess(response);
                this.dataTable.reload();
            },
            error: () => toastr.error(`Failed to update ${this.entityName}`)
        });
    }
    
    delete(id, name) {
        const url = this.destroyUrl.replace(':id', id);

        Swal.fire({
            title: 'Confirm Delete',
            html: `Are you sure you want to delete <span class="text-danger">${name}</span>?`,
            icon: "error",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken },
                    success: () => {
                        toastr.success(`${this.entityName} deleted successfully`);
                        this.dataTable.reload();
                    },
                    error: () => toastr.error(`Failed to delete ${this.entityName}`)
                });
            }
        });
    }


}