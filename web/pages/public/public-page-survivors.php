<section id="page-survivants">
  <div class="dropdown">
    <button class="btn btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Afficher par page :
    </button>
    <select id="displayedRows" class="custom-select custom-select-sm" style="width: inherit;">
      <option value="10" %1$s class="ajaxAction" data-ajaxaction="display">10 résultats</option>
      <option value="25" %2$s class="ajaxAction" data-ajaxaction="display">25 résultats</option>
      <option value="50" %3$s class="ajaxAction" data-ajaxaction="display">50 résultats</option>
    </select>
  </div>

  <table class="table table-striped table-sm" role="grid">
    <thead>
      <tr role="row">
        <th>&nbsp;</th>
        <th class="sorting%4$s ajaxAction" data-colsort="name" data-colorder="%4$s" data-ajaxaction="sort">Nom</th>
        <th>Zombivor</th>
        <th>Ultimate</th>
        <th>Extension</th>
        <th>Compétences</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>&nbsp;</th>
        <th>Nom</th>
        <th>Zombivor</th>
        <th>Ultimate</th>
        <th>Extension</th>
        <th>Compétences</th>
      </tr>
    </tfoot>
    <tbody>%5$s</tbody>
  </table>
  <nav>
    <ul class="pagination pagination-sm float-left">
      <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Affichés %6$s à %7$s sur %8$s résultats</a></li>
    </ul>
    <ul class="pagination justify-content-end pagination-sm">
      <li class="page-item%10$s"><a class="page-link ajaxAction" href="#" data-paged="1" data-ajaxaction="paged">&laquo;</a></li>
      %9$s
      <li class="page-item%11$s"><a class="page-link ajaxAction" href="#" data-paged="%12$s" data-ajaxaction="paged">&raquo;</a></li>
    </ul>
  </nav>
</section>
