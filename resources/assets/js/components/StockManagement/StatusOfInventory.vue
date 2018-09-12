<template>
    <div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-success">
                    <div class="panel-heading">Available Blood Stocks</div>
                    <InventoryTable />
                    <Available />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Available from './StatusOfInventory/Available'
import InventoryTable from '../Home/InventoryTable'
export default{
    components : {Available,InventoryTable}
}
</script>
