<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Available Blood Stocks</div>
                    <div class="panel-body">
                        <div class="row">
                            <InventoryTable class="col-lg-6" style="margin-top:4em;" />
                            <Available class="col-lg-6" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">Reserved Blood Stocks</div>
                    <div class="panel-body">
                        <div class="row">
                            <ReservedTable class="col-lg-6" style="margin-top:4em;" />
                            <ReservedChart class="col-lg-6" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Available from './StatusOfInventory/Available'
import InventoryTable from '../Home/InventoryTable'
import ReservedTable from './StatusOfInventory/ReservedTable'
import ReservedChart from './StatusOfInventory/ReservedChart'

export default{
    components : {Available,InventoryTable,ReservedTable,ReservedChart}
}
</script>
