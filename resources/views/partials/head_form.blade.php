<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            body, html {
  padding: 10px 10px 3px 10px;
  margin: 0;
  font-family: "Noto sans";
  height: 100%;
}

body {
  border-radius: 0.75em;
  border: 1px solid #22292f;
}

table {
  width: 100%;
}

thead {
  display: table-header-group;
}

tfoot {
  display: table-row-group;
}

tr {
  page-break-inside: avoid;
}

.table-info {
  border-radius: 0.75em;
  overflow: hidden;
  border-spacing: 0;
}
.table-info thead tr td {
  border-left: 1px solid #fff;
  border-top: 1px solid #5d6975;
  border-bottom: 1px solid #5d6975;
}
.table-info thead tr td:first-child {
  border-radius: 1em 0 0 0;
  border-left: 1px solid #5d6975;
}
.table-info thead tr td:last-child {
  border-radius: 0 0.75em 0 0;
  border-right: 1px solid #5d6975;
}
.table-info thead tr td:only-child {
  border-radius: 0.75em 0.75em 0 0;
}
.table-info table thead tr td:last-child {
  border-left: none;
}
.table-info tbody tr td {
  border-left: 1px solid #5d6975;
  border-bottom: 1px solid #5d6975;
}
.table-info tbody tr td:last-child {
  border-right: 1px solid #5d6975;
}
.table-info tbody tr:last-child td:last-child {
  border-right: 1px solid #5d6975;
  border-radius: 0 0 0.75em 0;
}
.table-info tbody tr:last-child td:first-child {
  border-radius: 0 0 0 0.75em;
}
.table-info tbody tr:last-child td:only-child {
  border-radius: 0 0 1em 1em;
}

.table-code {
  border-radius: 0.75em;
  border-spacing: 0;
}
.table-code tbody tr td:last-child {
  border-right: 1px solid #5d6975;
  border-bottom: 1px solid #5d6975;
}
.table-code tbody tr td:only-child {
  border-right: 1px solid #5d6975;
  border-left: 1px solid #5d6975;
}
.table-code tbody tr td:first-child {
  border-bottom: 1px solid #fff;
}
.table-code tbody tr:first-child td:last-child {
  border-top-right-radius: 0.75em;
  border-top: 1px solid #5d6975;
  border-right: 1px solid #5d6975;
}
.table-code tbody tr:first-child td:first-child {
  border-top-left-radius: 0.75em;
}
.table-code tbody tr:last-child td:first-child {
  border-bottom-left-radius: 0.75em;
  border-bottom: none;
}
.table-code tbody tr:last-child td:last-child {
  border-bottom-right-radius: 0.75em;
  border-right: 1px solid #5d6975;
}
.table-code tbody tr:last-child td:only-child {
  border-bottom-right-radius: 0.75em;
  border-bottom-left-radius: 0.75em;
  border-right: 1px solid #5d6975;
  border-left: 1px solid #5d6975;
  border-bottom: 1px solid #5d6975;
}

.table-collapse {
  border-collapse: collapse;
}

.border-grey-darker {
  border-color: #5d6975;
  border-style: solid;
  border-width: 1px;
}

.border {
  border-color: #5d6975;
  border-style: solid;
  border-width: 1px;
}

.no-border {
  border: none !important;
}

.border-solid {
  border-style: solid;
}

.border-dashed {
  border-style: dashed;
}

.border-dotted {
  border-style: dotted;
}

.border-none {
  border-style: none;
}

.border-darker {
  border-color: #22292f;
}

.border-b-2 {
  border-bottom-width: 2px;
}

.border-b-3 {
  border-bottom-width: 5px;
}

.border-b-4 {
  border-bottom-width: 4px;
}

.border-b {
  border-color: #22292f;
  border-style: solid;
  border-bottom-width: 1px;
}

.border-t {
  border-color: #22292f;
  border-style: solid;
  border-top-width: 1px;
}

.border-r {
  border-color: #22292f;
  border-style: solid;
  border-right-width: 1px;
}

.border-l {
  border-color: #22292f;
  border-style: solid;
  border-left-width: 1px;
}

.inline {
  display: inline;
}

.inline-block {
  display: inline-block;
}

.block {
  display: block;
}

.table {
  display: table;
}

.table-row {
  display: table-row;
}

.text-left {
  text-align: left;
}

.text-center {
  text-align: center;
}

.text-right {
  text-align: right;
}

.text-justify {
  text-align: justify;
}

.w-10 {
  width: 10%;
}

.w-15 {
  width: 15%;
}

.w-20 {
  width: 20%;
}

.w-25 {
  width: 25%;
}

.w-30 {
  width: 30%;
}

.w-33 {
  width: 33%;
}

.w-35 {
  width: 35%;
}

.w-38 {
  width: 38%;
}

.w-39 {
  width: 39.5%;
}

.w-40 {
  width: 40%;
}

.w-45 {
  width: 45%;
}

.w-50 {
  width: 50%;
}

.w-60 {
  width: 60%;
}

.w-65 {
  width: 65%;
}

.w-70 {
  width: 70%;
}

.w-75 {
  width: 75%;
}

.w-100 {
  width: 100%;
}

.mw-100 {
  max-width: 100%;
}

.p-10 {
  padding: 10px;
}

.p-5 {
  padding: 5px;
}

.py-100 {
  padding-top: 100px;
  padding-bottom: 100px;
}

.py-50 {
  padding-top: 50px;
  padding-bottom: 50px;
}

.py-15 {
  padding-top: 15px;
  padding-bottom: 15px;
}

.py-10 {
  padding-top: 10px;
  padding-bottom: 10px;
}

.py-5 {
  padding-top: 5px;
  padding-bottom: 5px;
}

.py-4 {
  padding-top: 4px;
  padding-bottom: 4px;
}

.py-3 {
  padding-top: 3px;
  padding-bottom: 3px;
}

.py-2 {
  padding-top: 2px;
  padding-bottom: 2px;
}

.px-100 {
  padding-left: 100px;
  padding-right: 100px;
}

.px-75 {
  padding-left: 75px;
  padding-right: 75px;
}

.px-50 {
  padding-left: 50px;
  padding-right: 50px;
}

.px-15 {
  padding-left: 15px;
  padding-right: 15px;
}

.px-10 {
  padding-left: 10px;
  padding-right: 10px;
}

.px-5 {
  padding-left: 5px;
  padding-right: 5px;
}

.px-4 {
  padding-left: 4px;
  padding-right: 4px;
}

.px-3 {
  padding-left: 3px;
  padding-right: 3px;
}

.px-2 {
  padding-left: 2px;
  padding-right: 2px;
}

.my-75 {
  margin-top: 75px;
  margin-bottom: 75px;
}

.my-50 {
  margin-top: 50px;
  margin-bottom: 50px;
}

.my-10 {
  margin-top: 10px;
  margin-bottom: 10px;
}

.my-5 {
  margin-top: 5px;
  margin-bottom: 5px;
}

.m-b-3 {
  margin-bottom: 3px;
}

.m-b-5 {
  margin-bottom: 5px;
}

.m-b-10 {
  margin-bottom: 10px;
}

.m-b-15 {
  margin-bottom: 15px;
}

.m-b-20 {
  margin-bottom: 20px;
}

.m-b-25 {
  margin-bottom: 25px;
}

.m-b-50 {
  margin-bottom: 50px;
}

.m-t-5 {
  margin-top: 5px;
}

.m-t-10 {
  margin-top: 10px;
}

.m-t-15 {
  margin-top: 15px;
}

.m-t-20 {
  margin-top: 20px;
}

.m-t-25 {
  margin-top: 25px;
}

.m-t-30 {
  margin-top: 30px;
}

.m-t-35 {
  margin-top: 35px;
}

.m-t-50 {
  margin-top: 50px;
}

.m-t-75 {
  margin-top: 75px;
}

.m-t-100 {
  margin-top: 100px;
}

.m-r-5 {
  margin-right: 5px;
}

.m-r-10 {
  margin-right: 10px;
}

.m-r-15 {
  margin-right: 15px;
}

.m-r-20 {
  margin-right: 20px;
}

.m-r-25 {
  margin-right: 25px;
}

.m-r-30 {
  margin-right: 30px;
}

.m-r-35 {
  margin-right: 35px;
}

.m-r-50 {
  margin-right: 50px;
}

.mlr-5 {
  margin-left: 5px;
}

.m-l-10 {
  margin-left: 10px;
}

.m-l-15 {
  margin-left: 15px;
}

.m-l-20 {
  margin-left: 20px;
}

.m-l-25 {
  margin-left: 25px;
}

.m-l-30 {
  margin-left: 30px;
}

.m-l-35 {
  margin-left: 35px;
}

.m-l-50 {
  margin-left: 50px;
}

.no-paddings {
  padding: 0px;
}

.no-margins {
  margin: 0px;
}

.pin-t {
  top: 0;
}

.pin-r {
  right: 0;
}

.pin-b {
  bottom: 0;
}

.pin-l {
  left: 0;
}

.pin-y {
  top: 0;
  bottom: 0;
}

.pin-x {
  right: 0;
  left: 0;
}

.pin {
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.pin-none {
  top: auto;
  right: auto;
  bottom: auto;
  left: auto;
}

.bg-grey-darker {
  background-color: #5d6975;
}

.bg-grey-lightest {
  background-color: #dae1e7;
}

.font-hairline {
  font-weight: 100;
}

.font-thin {
  font-weight: 200;
}

.font-light {
  font-weight: 300;
}

.font-normal {
  font-weight: 400;
}

.font-medium {
  font-weight: 500;
}

.font-semibold {
  font-weight: 600;
}

.font-bold {
  font-weight: 700;
}

.font-extrabold {
  font-weight: 800;
}

.font-black {
  font-weight: 900;
}

.italic {
  font-style: italic;
}

.uppercase {
  text-transform: uppercase;
}

.lowercase {
  text-transform: lowercase;
}

.capitalize {
  text-transform: capitalize;
}

.normal-case {
  text-transform: none;
}

.underline {
  text-decoration: underline;
}

.line-through {
  text-decoration: line-through;
}

.no-underline {
  text-decoration: none;
}

.text-black {
  color: #22292f;
}

.text-white {
  color: #fff;
}

.text-xxxs {
  font-size: 8px;
}

.text-xxs {
  font-size: 10px;
}

.text-xs {
  font-size: 12px;
}

.text-sm {
  font-size: 14px;
}

.text-sm-1 {
  font-size: 15px;
}

.text-base {
  font-size: 16px;
}

.text-base-1 {
  font-size: 17px;
}

.text-lg {
  font-size: 18px;
}

.text-xl {
  font-size: 20px;
}

.text-2xl {
  font-size: 24px;
}

.text-3xl {
  font-size: 30px;
}

.text-4xl {
  font-size: 36px;
}

.text-5xl {
  font-size: 3rem;
}

.rounded {
  border-top-left-radius: 0.7rem;
  border-top-right-radius: 0.7rem;
  border-bottom-right-radius: 0.7rem;
  border-bottom-left-radius: 0.7rem;
}

.rounded-t {
  border-top-left-radius: 0.7rem;
  border-top-right-radius: 0.7rem;
}

.rounded-tl {
  border-top-left-radius: 0.7rem;
}

.rounded-tr {
  border-top-right-radius: 0.7rem;
}

.rounded-bl {
  border-bottom-left-radius: 0.7rem;
}

.rounded-br {
  border-bottom-right-radius: 0.7rem;
}

.leading-none {
  line-height: 1;
}

.leading-tight {
  line-height: 1.25;
}

.leading-normal {
  line-height: 1.5;
}

.leading-loose {
  line-height: 2;
}

.list-roman {
  list-style-type: upper-roman;
}

.align-baseline {
  vertical-align: baseline;
}

.align-top {
  vertical-align: top;
}

.align-middle {
  vertical-align: middle;
}

.align-bottom {
  vertical-align: bottom;
}

.align-text-top {
  vertical-align: text-top;
}

.align-text-bottom {
  vertical-align: text-bottom;
}

.table-striped tr:nth-child(even) {
  background: #f1f5f8;
}

body {
  counter-reset: number 0;
}

.counter:before {
  counter-increment: number;
  content: counter(number) ".- ";
}
        </style>
    </head>
    <body>
        <br><br><br><br><br><br><br><br><br><br><br><br>
        
        <div class="page-break">
            <table class="w-100 ">
                <tr>
                    <th class="w-20 text-left no-padding no-margins align-middle">
                        <div class="text-center">
                            <img src="{{ public_path("/img/logo.png") }}" class="w-100">
                        </div>
                    </th>
                    <th class="w-50 align-top">
                        <span class="font-semibold uppercase leading-tight text-md" >
                            dsfasdfasdf
                        </span>
                    </th>
                    <th class="w-20 no-padding no-margins align-top">
                        @if(isset($code))
                            <table class="table-code no-padding no-margins">
                                <tbody>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Nº de Trámite</td>
                                        <td class="text-bold text-base">aaa</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Área</td>
                                        <td class="text-xs">aaaa</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                        <td class="text-xs">aaaa}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                        <td class="text-xs uppercase">aaaa</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table-code align-top no-padding no-margins">
                                <tbody>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Área</td>
                                        <td class="text-xs">aaaa</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                        <td class="text-xs">aaaa</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                        <td class="text-xs uppercase">aaaa</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif
                    </th>
                </tr>
                <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>
                <tr>
                    <td colspan="3" class="font-bold text-center text-xl uppercase">
                        aaaa
                        @if (isset($subtitle))
                        <br><span class="font-medium text-lg">aaaa</span>
                        @endif
                    </td>
                </tr>
                {{-- <tr><td colspan="3"></td></tr>
                <tr><td colspan="3"></td></tr> --}}

            </table>

            <div class="block">
                
                @yield('content')
            </div>
            <footer>
                @yield('footer')
            </footer>
            </div>
    </body>
</html>