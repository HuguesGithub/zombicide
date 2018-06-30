<div class="col-wrap">
  <div class="form-wrap">
    <h2>%1$s</h2>
    <form class="row" action="%2$s" method="post" id="addMission">
      <div class="col-4">
        <div class="form-field form-required term-name-wrap">
          <label for="title">Titre</label>
          <input type="text" size="40" value="%6$s" id="title" name="title">
        </div>
        <div class="form-field term-code-wrap half-column">
          <label for="code">Code</label>
              <input type="text" size="40" value="%7$s" id="code" name="code">
        </div>
        <div class="row col-12">
          <div class="col-5">
            <label for="difficultyId">Difficulté</label>%8$s
          </div>
          <div class="col-5 offset-md-1">
            <label for="durationId">Durée</label>%9$s
          </div>
          <div class="col-5">
            <label for="playerId">Nb de Survivants</label>%10$s
          </div>
          <div class="col-5 offset-md-1">
            <label for="origineId">Origine</label>%11$s
          </div>
        </div>
        <div class="form-field term-code-wrap half-column">
          <label for="expansions">Extensions</label>%12$s
        </div>
        <!-- 
      <div class="form-field term-description-wrap">
        <label for="description">Description</label>
        <textarea id="description" name="description">%5$s</textarea>
      </div>
      <div class="form-field term-author-wrap">
        <label for="author">Auteur</label>
        <input type="text" size="40" value="%15$s" id="author" name="author">
      </div>
      <div class="form-field term-officielle-wrap half-column">
        <label class="selectit" for="officielle"><input type="checkbox"%16$s id="officielle" name="officielle"> Mission officielle.</label>
      </div>
      <div class="form-field term-active-wrap half-column">
        <label class="selectit" for="active"><input type="checkbox"%6$s id="active" name="active"> Activer la mission.</label>
      </div>
      <div>
        <h3>Matériel nécessaire</h3>
        <div id="neededMaterial">%17$s</div>
        %18$s
      </div>
      <div>
        <h3>Objectifs &amp; Règles</h3>
        <div id="objectivesAndRules">%19$s</div>
        %20$s
      </div>
         -->
      </div>
      <div class="row col-8">
        <div class="col-4">
          <div class="objectivesAndRules"><h5>Règles</h5><ul id="ulAdminRules">%14$s</ul></div>        
        </div>
        <div class="col-4">
          <div class="objectivesAndRules"><h5>Mise en place</h5><ul id="ulAdminSettings">%15$s</ul></div>        
        </div>
        <div class="col-4">
          <div class="objectivesAndRules"><h5>Objectifs</h5><ul id="ulAdminObjectives">%16$s</ul></div>        
        </div>
      </div>
      <div class="col-4">
        <p class="cancel"><a class="page-title-action" href="%3$s">Annuler</a></p>
        <p class="submit">
              <input type="submit" value="%1$s" class="button button-primary" id="submit" name="submit">
          <input type="hidden" value="%4$s" id="id" name="id">
          <input type="hidden" value="%5$s" id="postAction" name="postAction">
        </p>
      </div>
      <div id="mapEditor" class="col-8">%13$s</div>
      </form>
  </div>
</div>
<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>