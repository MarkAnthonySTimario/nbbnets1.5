<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-bordered report_1">
                    <thead>
                        <tr>
                            <th rowspan="2">Province</th>
                            <th rowspan="2">Municipality<br/>/City</th>
                            <th rowspan="2">Date of<br/>MBD</th>
                            <th rowspan="2"># of <br/>Attendess</th>
                            <th rowspan="2">#<br/>complete<br/>collection</th>
                            <th rowspan="2">Inc</th>
                            <th rowspan="2">Spld</th>
                            <th colspan="2">Sex</th>
                            <th colspan="5">Age</th>
                            <th colspan="3">Donor</th>
                            <th colspan="2">Deferrals</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>M</th>
                            <th>F</th>
                            <th> 18</th>
                            <th>18-24</th>
                            <th>25-44</th>
                            <th>45-65</th>
                            <th>> 65</th>
                            <th>N</th>
                            <th>R</th>
                            <th>L</th>
                            <th>T</th>
                            <th>P</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>


table.report_1 {
  border-collapse: collapse;
  border: 2px solid #fff;
  font: normal 70%/140% verdana, arial, helvetica, sans-serif;
  color: #333;
  background: #fffff0;
  }
caption.report_1 {
  padding: 0 .4em .4em;
  text-align: left;
  font-size: 1em;
  font-weight: bold;
  text-transform: uppercase;
  color: #333;
  background: transparent;
  }
.report_1 td, .report_1 th {
  border: 1px solid #cc9;
  padding: .3em;
  }
.report_1 thead th,.report_1 tfoot th {
  border: 1px solid #fff;
  text-align: center;
  vertical-align: middle;
  font-size: 1em;
  font-weight: bold;
  color: #444;
  background: cornflowerblue;
  font-weight: bold;
  }
.report_1 tbody td a {
  background: transparent;
  color: #72724c;
  text-decoration: none;
  border-bottom: 1px dotted #cc9;
  }
.report_1 tbody td a:hover {
  background: transparent;
  color: #666;
  border-bottom: 1px dotted #72724c;
  }
.report_1 tbody th a {
  background: transparent;
  color: #72724c;
  text-decoration: none;
  font-weight:bold;
  border-bottom: 1px dotted #cc9;
  }
.report_1 tbody th a:hover {
  background: transparent;
  color: #666;
  border-bottom: 1px dotted #72724c;
  }
.report_1 tbody th,.report_1 tbody td {
  vertical-align: top;
  text-align: left;
  }
.report_1 tfoot td {
  border: 1px solid #996;
  }
.report_1 .odd {
  color: #333;
  background: #f7f5dc;
  }
.report_1 tbody tr:hover {
  color: #333;
  background: #fff;
  }
.report_1 tbody tr:hover th,
.report_1 tbody tr.odd:hover th {
  color: #333;
  background: #ddd59b;
  }
</style>