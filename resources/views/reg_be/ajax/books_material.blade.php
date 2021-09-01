<?php
$optional_books = \App\CtrOptionalFee::where('level',$current_level)->where('category','Books')->where('amount','>','0')->get();
$optional_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','Materials')->get();
$optional_other_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','Other Materials')->get();
$materials = \App\CtrMaterial::where('level',$current_level)->where('category','Materials')->get();
$other_materials = \App\CtrMaterial::where('level',$current_level)->where('category','Other Materials')->get();
?>                
                <table border="1"> 
                @if(count($optional_books)>0)
                <tr align="left"><td colspan="4"><strong>Books and Other Materials</strong></td><td>Sub Total</td></tr>
                <?php $i=1; $totalbook=0; $count=1;?>
                @foreach($optional_books as $optional)
                
                <tr><td>{{$count++}}</td><td>
                 {{$optional->subsidiary}}
                        @if($optional->is_required==1)
                        <small style="color:red"><i>Required</i></small>
                        @endif
                    </td>
                    <td>
                        @if($optional->is_required==1)
                        <input name="qty_books[{{$optional->id}}]" onKeyDown="return false" type="number" min="1" value="1" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)">
                        @else
                        <input name="qty_books[{{$optional->id}}]" onKeyDown="return false" type="number"  value="1" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)">
                        @endif
                    </td>
                <td>{{$optional->amount}}</td>
                <td align="left"><div class="book_display[]" id="book_display{{$optional->id}}"><?php  $totalbook=$totalbook+($optional->amount);?>{{$optional->amount}}</div></td></tr>
                @endforeach
                <tr><td colspan="4">Sub Total</td><td><div id="total_book">{{$totalbook}}</div></td></tr>
                @endif
                @if(count($optional_materials)>0)
                @foreach($optional_materials as $optional)
                <tr><td><input name="qty_books[{{$optional->id}}]" value="1" type="checkbox" checked="checked"  onclick="process_sub1({{$optional->id}},this.checked,{{$optional->amount}},this)"></td>
                <td colspan="3">
                    Required {{$optional->subsidiary}} <span class="warning">(SET)</span>
                    @if(count($materials)>0)
                   <ul>
                   @foreach($materials as $material)
                  <li>{{$material->particular}}
                   @endforeach
                   </ul>
                   @endif
                    </td>
                    
                <td align="left"><div id="book_display{{$optional->id}}">{{$optional->amount}}</div></td>
                </tr>
                @endforeach
                @endif 
                
                @if(count($optional_other_materials)>0)
                @foreach($optional_other_materials as $optional)
                <tr><td><input name="qty_books[{{$optional->id}}]" value="1" type="checkbox" checked="checked"  onclick="process_sub1({{$optional->id}},this.checked,{{$optional->amount}},this)"></td>
                <td colspan="3">
                 {{$optional->subsidiary}} <span class="warning">(SET)</span>
                 @if(count($other_materials)>0)
                   <ul>
                   @foreach($other_materials as $material)
                  <li>{{$material->particular}}
                   @endforeach
                   </ul>
                   @endif
                    </td>
                    
                <td align="left"><div id="book_display{{$optional->id}}">{{$optional->amount}}</div></td>
                </tr>
                @endforeach
                @endif
                 </table>

