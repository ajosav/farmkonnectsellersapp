<template>
    <div class="col-md-12">
        <div class="request_loader" v-if="loading">
            <span class="helper"></span>
        </div>
        <div class="card" :disabled="loading">
            <div class="card-body">
                <p class="italic text-danger">
                    <small
                        >The field labels marked with * are required input
                        fields.</small
                    >
                </p>
                <form id="product-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Product Name
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    id="name"
                                    aria-describedby="name"
                                    required=""
                                    v-model="product.name"
                                />
                                <span
                                    class="validation-msg text-danger"
                                    id="image-error"
                                    v-if="errors && errors.name"
                                >
                                    <template v-for="error in errors.name">
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_picker"
                                    ><strong
                                        >Availability [from - to]
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <div class="input-group" id="date_picker">
                                    <!-- <input type="text" name="date" class="form-control date" v-model="product.date" required> -->
                                    <date-range-picker
                                        ref="picker"
                                        :opens="datepicker.opens"
                                        :locale-data="{
                                            direction: 'ltr',
                                            format: 'yyyy/mm/dd',
                                            separator: ' - ',
                                            applyLabel: 'Apply',
                                            cancelLabel: 'Cancel'
                                        }"
                                        :minDate="datepicker.minDate"
                                        :maxDate="datepicker.maxDate"
                                        :startDate="datepicker.startDate"
                                        :endDate="datepicker.endDate"
                                        :singleDatePicker="
                                            datepicker.singleDatePicker
                                        "
                                        :timePicker="datepicker.timePicker"
                                        :timePicker24Hour="
                                            datepicker.timePicker24Hour
                                        "
                                        :showWeekNumbers="
                                            datepicker.showWeekNumbers
                                        "
                                        :showDropdowns="
                                            datepicker.showDropdowns
                                        "
                                        :autoApply="datepicker.autoApply"
                                        v-model="product.date"
                                        @update="formatDate"
                                    >
                                    </date-range-picker>
                                    <span
                                        class="validation-msg text-danger"
                                        id="image-error"
                                        v-if="errors && errors.startDate"
                                    >
                                        <template
                                            v-for="error in errors.startDate"
                                        >
                                            {{ error }}
                                        </template>
                                    </span>
                                    <span
                                        class="validation-msg text-danger"
                                        id="image-error"
                                        v-if="errors && errors.finishDate"
                                    >
                                        <template
                                            v-for="error in errors.finishDate"
                                        >
                                            {{ error }}
                                        </template>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Unit
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <multiselect
                                    v-model="product.unit_id"
                                    :options="defaultunits"
                                    :searchable="true"
                                    :close-on-select="true"
                                    track-by="unit_name"
                                    label="unit_name"
                                    @input="populateUnits(product.unit_id)"
                                ></multiselect>
                                <span
                                    class="validation-msg text-danger"
                                    id="image-error"
                                    v-if="errors && errors.unit_id"
                                >
                                    <template v-for="error in errors.unit_id">
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Sale Unit
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <multiselect
                                    v-model="product.sale_unit_id"
                                    :options="saleunits"
                                    :searchable="true"
                                    :close-on-select="true"
                                    track-by="unit_name"
                                    label="unit_name"
                                ></multiselect>
                                <span
                                    class="validation-msg text-danger"
                                    id="sale_unit-error"
                                    v-if="errors && errors.sale_unit_it"
                                >
                                    <template
                                        v-for="error in errors.sale_unit_it"
                                    >
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Purchase Unit
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <multiselect
                                    v-model="product.purchase_unit_id"
                                    :options="purchaseunits"
                                    :searchable="true"
                                    :close-on-select="true"
                                    track-by="unit_name"
                                    label="unit_name"
                                ></multiselect>
                                <span
                                    class="validation-msg text-danger"
                                    id="purchase-unit-error"
                                    v-if="errors && errors.purchase_unit_id"
                                >
                                    <template
                                        v-for="error in errors.purchase_unit_id"
                                    >
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Product quantity
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <input
                                    type="number"
                                    name="quantity"
                                    required
                                    class="form-control"
                                    step="any"
                                    min="1"
                                    v-model="product.quantity"
                                />
                                <span
                                    class="validation-msg text-danger"
                                    id="quantity-error"
                                    v-if="errors && errors.quantity"
                                >
                                    <template v-for="error in errors.quantity">
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Price (Per Product Unit)
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <input
                                    type="number"
                                    name="quantity"
                                    required
                                    class="form-control"
                                    step="any"
                                    min="1"
                                    v-model="product.price"
                                />
                                <span
                                    class="validation-msg text-danger"
                                    id="image-error"
                                    v-if="errors && errors.price"
                                >
                                    <template v-for="error in errors.price">
                                        {{ error }}
                                    </template>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="commodities_planted"
                                >Product category
                                <span class="text-danger">*</span></label
                            >
                            <multiselect
                                v-model="product.category"
                                :options="commodities"
                                :searchable="true"
                                :close-on-select="false"
                                :multiple="true"
                            ></multiselect>
                            <span
                                class="help-block text-danger"
                                v-if="errors && errors.category"
                                >{{ errors.category[0] }}</span
                            >
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Product Description</strong> </label>
                                <textarea name="product_description" class="form-control product_desc" v-model="product.description" rows="3"></textarea>
                                <!-- <input type="number" name="quantity" required class="form-control" step="any" min="1"> -->
                                <span
                                    class="validation-msg"
                                    id="name-error"
                                    v-if="errors && errors.description"
                                    >{{ errors.description[0] }}</span
                                >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label
                                    ><strong
                                        >Product Images (Not more than 500kb, maximum of 5 images in total)
                                        <span class="text-danger"
                                            >*</span
                                        ></strong
                                    >
                                </label>
                                <i
                                    class="fa fa-question-circle"
                                    data-toggle="tooltip"
                                    title="You can upload multiple image. Only .jpeg, .jpg, .png, .gif file can be uploaded. First image will be base image."
                                ></i>
                                <vue-dropzone
                                    ref="myVueDropzone"
                                    id="dropzone"
                                    :options="dropzoneOptions"
                                    v-on:vdropzone-sending="sendingEvent"
                                    v-on:vdropzone-success="dropzoneResponse"
                                    v-on:vdropzone-total-upload-progress="
                                        totalUploadProgress
                                    "
                                    v-on:vdropzone-queue-complete="
                                        uploadComplete
                                    "
                                    v-on:vdropzone-processing="uploadInit"
                                    v-on:vdropzone-error-multiple="
                                        dropzoneError
                                    "
                                ></vue-dropzone>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input
                            type="button"
                            value="Submit"
                            id="submit-btn"
                            @click.prevent="submitForm"
                            class="btn btn-success col-md-12"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            defaultunits: [],
            dropzoneOptions: {
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                autoProcessQueue: false,
                addRemoveLinks: true,
                dictDefaultMessage:
                    "<i class='fa fa-cloud-upload'></i>Click to Upload Product Images or Drag N Drop Image",
                duplicateCheck: true,
                maxFilesize: 0.5,
                maxFiles: 5,
                parallelUploads: 100,
                paramName: "image",
                uploadMultiple: true,
                url: "/product",
                headers: { "X-CSRF-TOKEN": this.$csrfToken },
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                    return time + file.name;
                },
                thumbnailWidth: 150
            },
            errors: [],
            loading: false,
            saleunits: [],
            purchaseunits: [],
            commodities: [],
            product: {
                name: null,
                quantity: null,
                date: {
                    startDate: moment(new Date()),
                    endDate: moment(new Date()).endOf("year")
                },
                price: null,
                unit_id: null,
                purchase_unit_id: null,
                sale_unit_id: null,
                category: null
            },
            datepicker: {
                minDate: null,
                maxDate: null,
                daysOfWeek: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                monthNames: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ],
                firstDay: 0,
                singleDatePicker: false,
                timePicker: false,
                timePicker24Hour: false,
                opens: "center",
                autoApply: true,
                showWeekNumbers: true,
                showDropdowns: true,
                dateRange: {
                    startDate: null,
                    endDate: null
                },
                dateFormat: "yyyy/mm/dd"
            }
        };
    },
    methods: {
        loadUnits() {
            axios
                .get("/product/fetch_units")
                .then(response => {
                    this.defaultunits = response.data;
                })
                .catch(response => {
                    toastr["error"](error.response.data.message);
                });
            axios
                .get("/all_commodities")
                .then(response => {
                    this.commodities = response.data;
                })
                .catch(response => {
                    toastr["error"](error.response.data.message);
                });
        },
        populateUnits(unit) {
            this.product.purchase_unit_id = null;
            this.product.sale_unit_id = null;
            if (unit) {
                axios
                    .get("/product/sale_unit/" + unit.id)
                    .then(response => {
                        (this.purchaseunits = response.data),
                            (this.saleunits = response.data);
                    })
                    .catch(response => {
                        toastr["error"](error.response.data.message);
                    });
            }
        },
        sendingEvent(file, xhr, formData) {
            tinyMCE.triggerSave();
            const description = $('textarea[name="product_description"]').val();
            formData.append("description", description);
            for (var key in this.product) {
                if (
                    key == "unit_id" ||
                    key == "purchase_unit_id" ||
                    key == "sale_unit_id"
                ) {
                    formData.append(key, this.product[key].id);
                } else if (key == "date") {
                    formData.append(
                        "startDate",
                        this.formatDateYmd(this.product[key].startDate)
                    );
                    formData.append(
                        "finishDate",
                        this.formatDateYmd(this.product[key].endDate)
                    );
                } else {
                    formData.append(key, this.product[key]);
                }
            }
        },
        dropzoneResponse(file, response) {},
        submitForm() {
            this.errors = [];
            for (var key in this.product) {
                if (this.product[key] == "" || this.product[key] == null) {
                    toastr["error"]("Please fill all the required fields");
                    return;
                }
            }
            if (this.$refs["myVueDropzone"].getQueuedFiles().length < 1) {
                swal.fire("Error", "Please upload at least an Image", "error");
            }
            this.$refs["myVueDropzone"].processQueue();
        },
        dropzoneError(file, message, xhr) {
            // console.log(this.$refs["myVueDropzone"].getRejectedFiles(file));

            if (xhr) {
                if (xhr.status == 422) {
                    this.errors = message.errors;
                }
                toastr["error"](message.message);
            } else {
                this.$refs["myVueDropzone"].removeFile(file);
                swal.fire("Error", message, "error");
            }
            this.loading = false;
        },
        totalUploadProgress(totaluploadprogress, totalBytes, totalBytesSent) {},
        uploadComplete() {
            for (var key in this.product) {
                if (key == "date") {
                    this.product[key].startDate = moment(new Date());
                    this.product[key].endDate =moment(new Date()).endOf("year");
                } else {
                    this.product[key] == null
                }      
            }
            this.loading = false;
            this.$refs["myVueDropzone"].removeAllFiles;
            location.href = "/home";
        },
        uploadInit(file) {
            this.loading = true;
        },
        formatDateYmd(date) {
            return moment(date).format("YYYY/MM/DD");
        },
        formatDate() {
            this.product.date.startDate = this.formatDateYmd(
                this.product.date.startDate
            );
            this.product.date.endDate = this.formatDateYmd(
                this.product.date.endDate
            );
        }
    },
    created() {},
    mounted() {
        this.loadUnits();
        Fire.$on("AfterCreate", () => {
            this.loadUnits();
        });
    }
};

    tinymce.init({
        selector: 'textarea.product_desc',
        height: 130,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        branding:false
    });


</script>

<style>
#axiosForm {
    position: relative;
}
#date_picker {
    width: 100%;
}
.daterangepicker {
    top: calc(1.6em + 0.75rem + 2px) !important;
}
.reportrange-text {
    min-width: 100% !important;
}
</style>
