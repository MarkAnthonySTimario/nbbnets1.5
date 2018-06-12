<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Available Blood Stocks
                    </div>
                    <div class="body">
                        <br/>
                        <div class="row form-horizontal">
                            <label for="" class="control-label col-lg-2 col-lg-offset-7" style="margin-top:-0.5em;">Search Donation ID</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control input-sm">
                            </div>
                        </div>
                        <br/>
                    </div>
                    <table class="table table-condensed table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Blood Type</th>
                                <th>Component</th>
                                <th>Donation ID</th>
                                <th>Volume</th>
                                <th>Date Collected</th>
                                <th>Expiration Date</th>
                                <th>Days Old</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <loadingInline label="Please wait, loading.."></loadingInline>
                                </td>
                            </tr>
                        </tbody>
                    </table>                        
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                loading : true, 
            }
        }
    }
</script>