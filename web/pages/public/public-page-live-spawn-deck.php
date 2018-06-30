<section id="page-live-spawn">
  <form action="#" method="post" class="row">
    <div id="spawnSetupSelection" class="col-3">%1$s</div>
    <div class="col-9 %2$s">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="labelInvasionSpanSelection">Sélection des cartes Invasion</span>
        </div>
        <input class="form-control" placeholder="[1-42]..." aria-describedby="labelInvasionSpanSelection" type="text" id="invasionSpanSelection" name="invasionSpanSelection">
      </div>
        
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="labelKeyAccess">Clé personnelle</span>
        </div>
        <input class="form-control" placeholder="Clé personnelle" aria-describedby="labelKeyAccess" type="text" id="keyAccess" name="keyAccess">
        <div class="input-group-append">
          <button id="genKey" class="btn btn-lg btn-outline-secondary" type="button"><i class="far fa-sun"></i></button>
        </div>
      </div>
        
      <div>
        <button class="btn btn-primary" type="submit">Générer</button>
      </div>
    </div>
    <div id="page-selection-result" class="col-9 row">%3$s</div>
  </form>
</section>