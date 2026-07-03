@extends('layouts.app')

@section('title', 'Modifier la matière')
@section('page-title', 'Modifier la matière')

@section('topbar-actions')
  <a href="{{ route('matieres.index') }}" class="btn-secondary">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
@endsection

@section('content')

<div style="max-width:560px;">
  <div class="card">
    <div class="card-header">
      <span class="card-title">
        <i class="ti ti-pencil"></i> Modifier — {{ $matiere->nom }}
      </span>
    </div>
    <div style="padding:24px;">
      <form method="POST" action="{{ route('matieres.update', $matiere) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nom de la matière <span style="color:var(--red);">*</span></label>
          <input type="text" name="nom" class="form-control"
                 value="{{ old('nom', $matiere->nom) }}" required>
          @error('nom') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Coefficient <span style="color:var(--red);">*</span></label>
          <select name="coefficient" class="form-select" required>
            @foreach([0.5, 1, 1.5, 2, 2.5, 3, 4, 5] as $coeff)
              <option value="{{ $coeff }}"
                {{ old('coefficient', $matiere->coefficient) == $coeff ? 'selected' : '' }}>
                {{ $coeff }}
              </option>
            @endforeach
          </select>
          @error('coefficient') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
  <label class="form-label">Note sur <span style="color:var(--red);">*</span></label>
  <select name="note_sur" class="form-select" required>
    <option value="10" {{ old('note_sur', $matiere->note_sur) == 10 ? 'selected' : '' }}>
      Sur 10
    </option>
    <option value="20" {{ old('note_sur', $matiere->note_sur) == 20 ? 'selected' : '' }}>
      Sur 20
    </option>
  </select>
  @error('note_sur') <div class="form-error">{{ $message }}</div> @enderror
</div>


        <!-- CLASSES -->
        <div class="form-group">
          <label class="form-label">Classes concernées <span style="color:var(--red);">*</span></label>
          <div style="border:1px solid var(--border); border-radius:8px; padding:12px;
                      display:flex; flex-wrap:wrap; gap:10px;">
            @foreach($classes as $classe)
            <label style="display:flex; align-items:center; gap:6px; cursor:pointer;
                          background:var(--bg); padding:6px 12px; border-radius:6px;
                          border:1px solid {{ in_array($classe->id, $classesAssociees) ? 'var(--green-mid)' : 'var(--border)' }};
                          background:{{ in_array($classe->id, $classesAssociees) ? 'var(--green-light)' : 'var(--bg)' }};">
              <input type="checkbox" name="classes[]" value="{{ $classe->id }}"
                     {{ in_array($classe->id, old('classes', $classesAssociees)) ? 'checked' : '' }}
                     style="accent-color:var(--green-dark);">
              <span style="font-size:13px; font-weight:500;">{{ $classe->nom }}</span>
            </label>
            @endforeach
          </div>
          @error('classes') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;
                    padding-top:16px; border-top:1px solid var(--border);">
          <a href="{{ route('matieres.index') }}" class="btn-secondary">Annuler</a>
          <button type="submit" class="btn-primary">
            <i class="ti ti-check"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection