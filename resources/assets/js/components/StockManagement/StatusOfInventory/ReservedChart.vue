<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <LoadingInline label="Please wait, loading.." v-if="loading" />
                <div v-if="!loading">
                    <GChart
                        type="ColumnChart"
                        :data="chartData"
                        :options="chartOptions"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { GChart } from 'vue-google-charts'
export default {
    props : ['height'],
    components : {GChart},
    data () {
        return {
            // Array will be automatically processed with visualization.arrayToDataTable function
            loading : true,
            chartData: null,
            chartOptions: {
                chart: {
                    title: 'Status of Inventory',
                    subtitle: 'Available Blood Stocks per Blood Type and Component',
                },
                height : this.height ? this.height : 400
            }
        }
    },
    mounted(){
        this.getData()
    },
    methods : {
        getData(){
            let {facility_cd} = this.$session.get('user');
            this.loading = true;
            this.$http.get(this,'chart/reservedchart/'+facility_cd)
            .then(({data}) => {
                let chartData = []
                chartData.push(this.getDataHeaderRow())
                _.each(data,d => {
                    chartData.push(d)
                })
                this.chartData = chartData
                this.loading = false;
            })
        },
        getDataHeaderRow(){
            let components = this.$session.get('components')
            let header = ['Blood Type'];
            _.each(components,(cn,cd) => {
                header.push(cn)
            })
            return header
        }
    }
}
</script>
