@extends('layout.index')
@section('title', 'Mágias')
@push('scripts')
<script>
    $(document).ready(function () {
    $('#table').DataTable({
      "bFilter": true,
      paging: false,
      "sDom":"lrtip",
      "language": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "Nenhum registro encontrado",
        "sSearch": "",
        "oPaginate": {
          "sNext": "Próximo",
          "sPrevious": "Anterior",
          "sFirst": "Primeiro",
          "sLast": "Último"
        },
        "oAria": {
          "sSortAscending": ": Ordenar colunas de forma ascendente",
          "sSortDescending": ": Ordenar colunas de forma descendente"
        }
      }
    });

    let oTable;
    oTable = $('#table').dataTable();
    $('.vocation').change(function() {
      oTable.fnFilter($(this).val(), 10);
    });

    $('.group').change(function() {
      oTable.fnFilter($(this).val(), 3);
    });

    $('.type').change(function() {
      oTable.fnFilter($(this).val(), 2);
    });

    $('.premium').change(function() {
      console.log($(this).val());
      oTable.fnFilter($(this).val(), 9);
    });
  });
</script>
@endpush
@section('content')
<div class="container bg-border" style="margin-top:120px">
    <div class="bg-headline">
        <span>Spells</span>
    </div>
    <div class="bg-content">
        <div class="container p-0">
            <div class="bg-title">Spells</div>
            <div class="main-content p-0">
                <div class="table-responsive">
                    <table id="table" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>*</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Group</th>
                            <th>Mana</th>
                            <th>Level</th>
                            <th>Magic Level</th>
                            <th>Exhaustion</th>
                            <th>Soul</th>
                            <th>Premium</th>
                            <th>Vocações</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($spells as $spell)
                            @php
                                $mana = 0;
                                if($spell['mana'] && $spell['mana'])
                                    $mana = $spell['mana'] ;
                                else if ($spell['manapercent'] && $spell['manapercent'])
                                    $mana = $spell['manapercent'].'%';

                                $prem = $spell['prem'] == 1 ? "Sim" : "Não";
                                $maglv = $spell['maglv'] && $spell['maglv'] ? $spell['maglv'] : 0;
                                $soul = $spell['soul'] && $spell['soul'] ? $spell['soul'] : 0;
                                $vocation = @$spell['vocation'] && @$spell['vocation'] ? implode(", ", $spell['vocation']) : 'Todas';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><b>{{ ucwords($spell['name']) }}</b> ({{ ucwords($spell['words']) }})</td>
                                <td>{{ $spell['type'] }}</td>
                                <td>{{ $spell['group'] }}</td>
                                <td>{{ $mana }}</td>
                                <td>{{ $spell['lvl'] }}</td>
                                <td>{{ $maglv }}</td>
                                <td>{{ $spell['exhaustion'] }}</td>
                                <td>{{ $soul }}</td>
                                <td>{{ $prem }}</td>
                                <td>{{ $vocation }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <form action="{{ route('spells.index') }}" method="POST">
                    @csrf
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <th>Vocation</th>
                            <th>Group</th>
                            <th>Type</th>
                            <th>Premium</th>
                        </thead>
                        <tbody>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input vocation" type="radio" name="voc" id="voc-all" value="" checked>
                                            <label class="form-check-label" for="voc-all">Todos</label>
                                        </div>
                                        @foreach ($vocs as $vocs_id => $voc)
                                        <div class="form-check">
                                            <input class="form-check-input vocation" type="radio" name="voc" id="voc-{{ $vocs_id }}" value="{{ $voc }}">
                                            <label class="form-check-label" for="voc-{{ $vocs_id }}">{{ htmlspecialchars($voc) }}</label>
                                        </div>
                                        @if($vocs_id % 4 === 0)
                                        <p></p>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input group" type="radio" name="group" id="group-all" value="" checked>
                                            <label class="form-check-label" for="group-all">Todos</label>
                                        </div>
                                        @foreach ($groups as $group_id => $group)
                                        <div class="form-check">
                                            <input class="form-check-input group" type="radio" name="group" id="group-{{ $group_id }}" value="{{ $group }}">
                                            <label class="form-check-label" for="group-{{ $group_id }}">{{ htmlspecialchars($group) }}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input type" type="radio" name="type" id="type-all" value="" checked>
                                            <label class="form-check-label" for="type-all">Todos</label>
                                        </div>
                                        @foreach ($types as $type_id => $type)
                                        <div class="form-check">
                                            <input class="form-check-input type" type="radio" name="type" id="type-{{ $type_id }}" value="{{ $type }}">
                                            <label class="form-check-label" for="type-{{ $type_id }}">{{ htmlspecialchars($type) }}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input premium" type="radio" name="premium" id="premium-all" value="" checked>
                                            <label class="form-check-label" for="premium-all">Todos</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input premium" type="radio" name="premium" id="premium-yes" value="Sim" >
                                            <label class="form-check-label" for="premium-yes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input premium" type="radio" name="premium" id="premium-no" value="Não" >
                                            <label class="form-check-label" for="premium-no">No</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
