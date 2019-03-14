<template>
    <div>
        <button type="button" data-toggle="modal" class="btn btn-warning" vertical-align="center" data-target="#modifymodal">
            Modify
        </button>

        <!-- Modal -->
        <div class="modal" id="modifymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modify Appointment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="card-title text-center">Schedule an Appointment</h2>
                                        <search-appointment></search-appointment>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="card-title text-center">Add Appointment</h2><br>
                                        <add-appointment :apmt="apmt" :is-nurse="false"></add-appointment>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchAppointment from "./SearchAppointment";
    import AddAppointment from "./AddAppointment";
    export default {
        name: "ModifyAppointmentModal",
        Components: {
            SearchAppointment,
            AddAppointment
        },
        props: {
          apmt: Object,
        },
    }
</script>

<style scoped>

</style>
