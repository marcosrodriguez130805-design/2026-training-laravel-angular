import { Component, EventEmitter, Input, OnInit, Output, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { 
  IonItem, IonLabel, IonInput, IonButton, 
  IonText, IonSelect, IonSelectOption, IonSpinner 
} from '@ionic/angular/standalone';
import { Table } from '../../../../services/tables/table.model';
import { Zone } from '../../../../services/zones/zone.model';

@Component({
  selector: 'app-table-form',
  templateUrl: './table-form.component.html',
  standalone: true,
  imports: [
    CommonModule, ReactiveFormsModule, IonItem, IonLabel, 
    IonInput, IonButton, IonText, IonSelect, IonSelectOption, 
    IonSpinner
  ],
  // 🛠️ Solución radical al error NG2008: metemos los estilos en línea y nos olvidamos del archivo físico defectuoso
  styles: [`
    .custom-input {
      --background: #f1f2f6;
      --border-radius: 8px;
      --padding-start: 12px;
      margin-bottom: 4px;
    }
    .custom-input ion-label {
      font-weight: 600;
      color: #2f3542;
      margin-bottom: 8px !important;
    }
    .error-msg {
      margin-left: 12px;
      font-size: 12px;
    }
    .buttons-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-top: 24px;
    }
    ion-select {
      width: 100%;
      --placeholder-color: #a4b0be;
      --placeholder-opacity: 1;
    }
  `]
})
export class TableFormComponent implements OnInit {
  private fb = inject(FormBuilder);

  @Input() table: Table | null = null;
  @Input() zones: Zone[] = [];
  @Input() isSaving: boolean = false;
  
  @Output() save = new EventEmitter<any>();
  @Output() cancel = new EventEmitter<void>();

  form: FormGroup = this.fb.group({
    name: ['', [Validators.required, Validators.minLength(2)]],
    zone_uuid: ['', [Validators.required]]
  });

  ngOnInit() {
    if (this.table) {
      this.form.patchValue({
        name: this.table.name,
        zone_uuid: this.table.zone_uuid || (this.table as any).zoneUuid
      });
    }
  }

  submit() {
    if (this.form.valid) {
      this.save.emit(this.form.value);
    }
  }
}