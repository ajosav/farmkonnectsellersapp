<template>
    <div class="col-md-12" id="axiosForm">
        <div class="request_loader" v-if="loading">
            <span class="helper"></span>
        </div>
        <div class="box box-primary">
            <form role="form" @submit.prevent="status ? updateProfile() : createProfile()">
                <fieldset :disabled="loading">
                    <div class="box-body">
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" v-model="profile.name" id="name" placeholder="Company/Organization/Individual name" :disabled="toggleStatus">
                                <span class="help-block text-danger" v-if="errors && errors.name">{{errors.name[0]}}</span>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Email</label>
                                    <input type="text" class="form-control" v-model="profile.email"  placeholder="example@example.com" :disabled="toggleStatus">
                                    <span class="help-block text-danger" v-if="errors && errors.email">{{errors.email[0]}}</span>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" v-model="profile.phone" placeholder="Phone Number" :disabled="toggleStatus">
                                <span class="help-block text-danger" v-if="errors && errors.phone">{{errors.phone[0]}}</span>
                            </div>

                            <div class="form-group col-md-6">
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
                                <span class="help-block text-danger" v-if="errors && errors.state">{{errors.state[0]}}</span>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">LGA</label>
                                <select
                                    name="lga"
                                    id="lga"
                                    v-model="profile.lg"
                                    class="form-control select-lga"
                                    required
                                    :disabled="toggleStatus"
                                >
                                </select>
                                <span class="help-block text-danger" v-if="errors && errors.lg">{{errors.lg[0]}}</span>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="location">Location/Address</label>
                                <textarea class="form-control" rows="3" placeholder="Address" v-model="profile.address" style="resize:none" :disabled="toggleStatus"></textarea>
                                <span class="help-block text-danger" v-if="errors && errors.address">{{errors.address[0]}}</span>
                                <!-- <input type="text" class="form-control" v-model="profile.location" id="location" placeholder="Location"> -->
                            </div>
                            <div class="form-group col-md-12">
                                <label>Other Info.</label>
                                <textarea class="form-control" rows="3" placeholder="Warehouse, etc" v-model="profile.other_info" style="resize:none" :disabled="toggleStatus"></textarea>
                                <span class="help-block text-danger" v-if="errors && errors.other_info">{{errors.other_info[0]}}</span>
                            </div>

                        </div>

                    </div>
                <!-- /.box-body -->

                    <div class="box-footerext text-right mb-3">
                        <button @click.prevent="activateEdit" class="btn btn-primary btn-lg col-md-3 submit_action" v-show="toggleStatus">Edit</button>
                        <button type="submit" class="btn btn-lg col-md-3 submit_action" :class="btn_success" v-show="!toggleStatus">{{profile.uuid == null ? 'Submit' : 'Update'}}</button>
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
            required: true
        }
    },
    data() {
        return {
            states: ['Abia', 'Adamawa', 'AkwaIbom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamafara'],
            value: [],
            profile: null,
            commodities: [],
            errors: null,
            toggleStatus: this.status,
            btn_success: 'btn-primary',
            loading: false,
        }
    },
    methods: {
        loadCommodities() {
            this.profile = {
                name: null,
                email: null,
                phone: null,
                address: null,
                other_info: null,
                state: null,
                lg: null,
                uuid: null
            }
            if(this.toggleStatus) {
                 axios.get('/getProfile')
                .then(response => {
                    this.profile = response.data.data
                    this.$selectState(this.profile.state)
                }).catch(error => {
                    toastr["error"](error.response.data.message)
                    return
                })
            }

            axios.get('/all_commodities')
            .then((response) => {
                this.commodities = response.data
            })
        },

        selected() {
            this.profile.commodities_planted = $('#commodity_id').selectpicker('val');
        },

        createProfile(){
            this.loading = true;
            $('.submit_action').attr('disabled', true);
            axios.post('/profile', this.profile)
            .then(response => {
                swal.fire(
                    'Success',
                    'User Profile Successfully Created',
                    'success'
                )
                this.commodities = [];
                this.errors = null;
                this.toggleStatus = !this.toggleStatus;
                $('.submit_action').attr('disabled', false);
                Fire.$emit('AfterCreate');
            })

            .catch(error => {

                if(error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
                toastr["error"](error.response.data.message)

                $('.submit_action').attr('disabled', false);
            }).finally(() => {
                this.loading = false;
            })

        },

        activateEdit() {
            this.btn_success = "btn-success";
            this.toggleStatus = !this.toggleStatus;
        },
        updateProfile() {
            this.loading = true;
            $('.submit_action').attr('disabled', true);
            axios.post(`profile`, this.profile)
            .then(response => {
                swal.fire(
                    'Sucess',
                    'Profile Updated',
                    'success'
                )
                $('.submit_action').attr('disabled', false);
                this.commodities = [];
                this.errors = null;
                this.btn_success = "btn-primary";
                this.toggleStatus = !this.toggleStatus;
                Fire.$emit('AfterCreate');
            })

            .catch((error) => {
                if(error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
                toastr["error"](error.response.data.message)

                $('.submit_action').attr('disabled', false);
            }).finally(() => {
                this.loading = false;
            })

        }

    },
    created() {
        this.loadCommodities();
        Fire.$on('AfterCreate', () => {
            this.loadCommodities()
        })
    }


}

</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css">

</style>

<style>
    #axiosForm {
        position: relative;
    }
</style>
