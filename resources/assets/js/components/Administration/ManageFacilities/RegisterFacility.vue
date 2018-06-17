<template>
    <div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Register New Facility
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 form-horizontal">
                                <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-8 -->
                                <table class="table table-bordered table-condensed">
                                    <tbody>
                                        <tr>
                                            <td class="text-center">Owner</td>
                                            <td class="text-center">Contact Person</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <photo-capture :key="photo1"></photo-capture>
                                            </td>
                                            <td class="text-center">
                                                <photo-capture :key="photo2"></photo-capture>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <button class="btn btn-default btn-sm">Save</button>
                                    <router-link to="/ManageFacilities" class="btn btn-danger btn-sm">Cancel</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}
.form-group.required .control-label:after {
  content:"*";
  color:red;
}
.error {
    font-size:12px;
    margin-top:0.5em;
}
</style>