<template>
  <div class="col-md-12" id="axiosForm">
    <div class="request_loader" v-if="loading">
      <span class="helper"></span>
      <!-- <img class="loaderImg" src="/images/gifs/ajax-loader.gif"> -->
    </div>
    <div class="box box-primary">
      <form
        role="form"
        @submit.prevent="status ? updateProfile() : createProfile()"
        enctype="multipart/form-data"
        method="POST"
        action="/profile"
      >
        <fieldset :disabled="loading">
          <div class="box-body">
            <div class="form-row">
              <div class="form-group col-md-5">
                <label for="name">Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="profile.name"
                  id="name"
                  placeholder="Company/Organization/Individual name"
                  :disabled="toggleStatus"
                  required
                />
                <span class="help-block text-danger" v-if="errors && errors.name">{{errors.name[0]}}</span>
              </div>

              <div class="form-group col-md-4">
                <label>Email</label>
                <input
                  type="email"
                  class="form-control"
                  v-model="profile.email"
                  placeholder="example@example.com"
                  :disabled="toggleStatus"
                  required
                />
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.email"
                >{{errors.email[0]}}</span>
              </div>

              <div class="form-group col-md-3">
                <label>Phone Number</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="profile.phone"
                  placeholder="Phone Number"
                  :disabled="toggleStatus"
                  required
                />
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.phone"
                >{{errors.phone[0]}}</span>
              </div>

              <div class="form-group col-md-4">
                <label class="control-label">State</label>

                <multiselect
                  v-model="profile.state"
                  :options="states"
                  :searchable="true"
                  :close-on-select="true"
                  :disabled="toggleStatus"
                  placeholder="select a state"
                  @input="$selectState(profile.state)"
                ></multiselect>
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.state"
                >{{errors.state[0]}}</span>
              </div>

              <div class="form-group col-md-4">
                <label class="control-label">LGA</label>
                <select
                  name="lga"
                  id="lga"
                  v-model="profile.lg"
                  class="form-control select-lga"
                  required
                  :disabled="toggleStatus"
                ></select>
                <span class="help-block text-danger" v-if="errors && errors.lg">{{errors.lg[0]}}</span>
              </div>

              <div class="form-group col-md-4">
                <label class="control-label">Rate</label>
                <input
                  type="number"
                  class="form-control"
                  v-model="profile.rate"
                  id="rate"
                  name="rate"
                  placeholder="Rate per KM"
                  step="5"
                  :disabled="toggleStatus"
                  required
                />
                <span class="help-block text-danger" v-if="errors && errors.rate">{{errors.rate[0]}}</span>
              </div>

              <div class="form-group col-md-12">
                <label for="location">Location/Address</label>
                <textarea
                  class="form-control"
                  rows="3"
                  placeholder="Address"
                  v-model="profile.address"
                  style="resize:none"
                  :disabled="toggleStatus"
                  required
                ></textarea>
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.address"
                >{{errors.address[0]}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="color-palette-set">
                  <div class="bg-green disabled color-palette p-1">
                    <span>Vehicle Information</span>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label>Registration No.</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Reg. No."
                  v-model="profile.registration_no"
                  :disabled="toggleStatus"
                  required
                />
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.registration_no"
                >{{errors.registration_no[0]}}</span>
              </div>
              <div class="form-group col-md-6">
                <label>Chasis No.</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Chasis No."
                  v-model="profile.chasis_no"
                  :disabled="toggleStatus"
                  required
                />
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.chasis_no"
                >{{errors.chasis_no[0]}}</span>
              </div>
              <div class="form-group col-md-8">
                <label>
                  <strong>Driver's License</strong>
                </label>
                <input
                  type="file"
                  @change="uploadLicense"
                  class="form-control"
                  placeholder="Chasis No."
                  :disabled="toggleStatus"
                />
                <!-- <vue-dropify :multiple='dropify.multiple' :message="dropify.message"></vue-dropify> -->
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.driving_license"
                >{{errors.driving_license[0]}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>
                  <strong>License Expiration</strong>
                </label>
                <input
                  type="date"
                  v-model="profile.license_expiration"
                  class="form-control"
                  placeholder="Expirationn Date"
                  :min="min_date"
                  :disabled="toggleStatus"
                  required
                />
                <!-- <vue-dropify :multiple='dropify.multiple' :message="dropify.message"></vue-dropify> -->
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.license_expiration"
                >{{errors.license_expiration[0]}}</span>
              </div>
            </div>
            <div class="form-row" v-for="(fields, index) in profile.other_fields" :key="fields.id">
              <div class="form-group col-md-4">
                <multiselect
                  v-model="fields.title"
                  :options="valid_docs"
                  :disabled="toggleStatus"
                  @input="validateDuplicate(fields, index)"
                ></multiselect>
              </div>
              <div class="form-group col-md-4">
                <input
                  type="file"
                  @change="uploadDoc(index)"
                  class="form-control"
                  placeholder="Chasis No."
                  :disabled="toggleStatus"
                />
              </div>
              <div class="form-group col-md-3">
                <input
                  type="date"
                  v-model="fields.expiry_date"
                  class="form-control"
                  placeholder="Expirationn Date"
                  :min="min_date"
                  :disabled="toggleStatus"
                  required
                />
              </div>

              <span
                v-show="!toggleStatus"
                @click="removeField(index)"
                class="text-danger text-center col-md-1"
              >
                <i class="fas fa-minus-circle"></i>
              </span>
            </div>

            <div class="form-row" v-show="!toggleStatus">
              <label id="add_doc" @click="addField">
                <span class="text-success">
                  <i class="fas fa-plus"></i> Add other documents
                </span>
              </label>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="location">Other info</label>
                <textarea
                  class="form-control"
                  rows="3"
                  placeholder="Other Information"
                  v-model="profile.other_info"
                  style="resize:none"
                  :disabled="toggleStatus"
                ></textarea>
                <span
                  class="help-block text-danger"
                  v-if="errors && errors.other_info"
                >{{errors.other_info[0]}}</span>
              </div>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footerext text-right mb-3">
            <button
              @click.prevent="activateEdit"
              class="btn btn-primary btn-lg col-md-3 submit_action"
              v-show="toggleStatus"
            >Edit</button>
            <button
              type="submit"
              class="btn btn-lg col-md-3 submit_action"
              :class="btn_success"
              v-show="!toggleStatus"
            >{{profile.uuid == null ? 'Submit' : 'Update'}}</button>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    status: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      states: [
        "Abia",
        "Adamawa",
        "AkwaIbom",
        "Anambra",
        "Bauchi",
        "Bayelsa",
        "Benue",
        "Borno",
        "Cross River",
        "Delta",
        "Ebonyi",
        "Edo",
        "Ekiti",
        "Enugu",
        "FCT",
        "Gombe",
        "Imo",
        "Jigawa",
        "Kaduna",
        "Kano",
        "Katsina",
        "Kebbi",
        "Kogi",
        "Kwara",
        "Lagos",
        "Nasarawa",
        "Niger",
        "Ogun",
        "Ondo",
        "Osun",
        "Oyo",
        "Plateau",
        "Rivers",
        "Sokoto",
        "Taraba",
        "Yobe",
        "Zamafara",
      ],
      value: [],
      profile: null,
      commodities: [],
      errors: null,
      valid_docs: [
        "National Certificate of Road Worthiness",
        "Vehicle Registration",
        "Motor Vehicle Insurance",
        "Hackney Permit",
      ],
      toggleStatus: this.status,
      btn_success: "btn-primary",
      loading: false,
      min_date: "",
    };
  },
  methods: {
    loadCommodities() {
      this.profile = {
        name: null,
        email: null,
        phone: null,
        rate: null,
        address: null,
        other_info: null,
        state: null,
        lg: null,
        uuid: null,
        registration_no: null,
        chasis_no: null,
        driving_license: null,
        license_expiration: null,
        other_fields: [],
        other_info: null,
      };
      if (this.toggleStatus) {
        axios
          .get("/getProfile")
          .then((response) => {
            this.profile = response.data.data;
            this.$selectState(this.profile.state);
          })
          .catch((error) => {
            toastr["error"](error.response.data.message);
          });
      }

      axios.get("/all_commodities").then((response) => {
        this.commodities = response.data;
      });
    },
    uploadLicense(event) {
      const fileObj = event.target.files[0];
      if (fileObj["size"] > 2111775) {
        swal.fire({
          icon: "error",
          title: "Oops...",
          text: "File size can't be more than 2MB",
        });
        return false;
      }
      let reader = new FileReader();
      reader.onloadend = (fileObj) => {
        this.profile.driving_license = reader.result;
      };
      reader.readAsDataURL(fileObj);
      return true;
    },
    uploadDoc(index) {
      const fileObj = event.target.files[0];
      if (fileObj["size"] > 2111775) {
        swal.fire({
          icon: "error",
          title: "Oops...",
          text: "File size can't be more than 2MB",
        });
        return false;
      }
      let reader = new FileReader();
      reader.onloadend = (fileObj) => {
        this.profile.other_fields[index].document = reader.result;
      };
      reader.readAsDataURL(fileObj);
      return true;
    },
    createProfile() {
      this.loading = true;
      $(".submit_action").attr("disabled", true);
      axios
        .post("/profile", this.profile)
        .then((response) => {
          swal.fire("Success", "User Profile Successfully Created", "success");
          this.commodities = [];
          this.errors = null;
          this.toggleStatus = !this.toggleStatus;
          $(".submit_action").attr("disabled", false);
          Fire.$emit("AfterCreate");
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          toastr["error"](error.response.data.message);

          $(".submit_action").attr("disabled", false);
        })
        .finally(() => {
          this.loading = false;
        });
    },

    activateEdit() {
      this.btn_success = "btn-success";
      this.toggleStatus = !this.toggleStatus;
    },
    updateProfile() {
      if (this.validateFields(this.profile.other_fields) == false) {
        return;
      }
      this.loading = true;
      $(".submit_action").attr("disabled", true);
      axios
        .post(`profile`, this.profile)
        .then((response) => {
          swal.fire("Sucess", "Profile Updated", "success");
          $(".submit_action").attr("disabled", false);
          this.commodities = [];
          this.errors = null;
          this.btn_success = "btn-primary";
          this.toggleStatus = !this.toggleStatus;
          Fire.$emit("AfterCreate");
        })

        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          toastr["error"](error.response.data.message);

          $(".submit_action").attr("disabled", false);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    addField() {
      this.profile.other_fields.push({
        title: null,
        document: null,
        expiry_date: null,
      });
    },
    removeField(index) {
      this.profile.other_fields.splice(index, 1);
    },
    validateDuplicate(fields, index) {
      var current_field = [];
      if (this.profile.other_fields.length > 1) {
        this.profile.other_fields.forEach((element) => {
          if (element.document !== null && element.title == fields.title) {
            swal.fire(
              "Error",
              `${fields.title} cannot be selected twice`,
              "error"
            );
            this.removeField(index);
            return true;
          } else {
            if (element.title == fields.title) {
              current_field.push(fields);
            }
          }
        });
        if (current_field.length > 1) {
          swal.fire("Error", "You already Selected " + fields.title, "error");
          this.removeField(index);
        }
      }

      return true;
    },
    formatDate() {
      var d = new Date(),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    },
    validateFields(fields) {
      try {
        fields.forEach((el, index) => {
          if (
            el.document == null ||
            el.title == null ||
            el.expiry_date == null
          ) {
            toastr["error"](
              "Other Informations title and attachment must be filled"
            );
            throw false;
          }
        });
        throw true;
      } catch (e) {
        if (e) {
          return true;
        }
        return false;
      }
    },
  },
  created() {
    this.min_date = this.formatDate();
    this.loadCommodities();
    Fire.$on("AfterCreate", () => {
      this.loadCommodities();
    });
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
#axiosForm {
  /* Components Root Element ID */
  position: relative;
}
#add_doc {
  cursor: pointer;
}

#add_doc:hover {
  text-decoration: underline;
  text-decoration-color: #38c172;
  text-decoration-thickness: 1px;
}
</style>
