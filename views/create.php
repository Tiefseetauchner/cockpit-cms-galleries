<kiss-container class="kiss-margin" size="small">

    <ul class="kiss-breadcrumbs">
        <li><a href="<?php echo $this->route('/galleries')?>"><?php echo t('Galleries')?></a></li>
    </ul>

    <vue-view>
        <template>

            <div class="kiss-margin-large-bottom kiss-size-4">
                <strong v-if="!isUpdate"><?php echo t('Create gallery')?></strong>
                <strong v-if="isUpdate"><?php echo t('Edit model')?></strong>
            </div>

            <form :class="{'kiss-disabled':saving}" @submit.prevent="save">

                <kiss-grid cols="2@m" class="kiss-margin">
                    <div :class="{'kiss-disabled': isUpdate}">
                        <label><?php echo t('Name')?></label>
                        <input class="kiss-input" type="text" pattern="[a-zA-Z0-9_]+" v-model="model.name" :disabled="isUpdate" required>
                    </div>
                    <div>
                        <label><?php echo t('Display name')?></label>
                        <input class="kiss-input" type="text" v-model="model.label">
                    </div>
                </kiss-grid>

                <div class="kiss-margin">
                    <label><?php echo t('Info')?></label>
                    <textarea class="kiss-input kiss-textarea" style="height:100px;" v-model="model.info"></textarea>
                </div>

                <div class="kiss-margin kiss-margin-large-top">

                    <kiss-tabs class="kiss-margin-large">
                        <tab :caption="'Other'">

                            <div class="kiss-flex">
                                <div class="kiss-flex-1">
                                    <label><?php echo t('Color')?></label>
                                    <div class="kiss-size-xsmall kiss-color-muted kiss-margin-xsmall-top">
                                        <?php echo t('Model accent color')?>
                                    </div>
                                </div>
                                <field-color v-model="model.color" size="30"></field-color>
                            </div>

                            <hr>

                            <div class="kiss-flex">
                                <div class="kiss-flex-1">
                                    <label><?php echo t('Icon')?></label>
                                    <div class="kiss-size-xsmall kiss-color-muted kiss-margin-xsmall-top">
                                        <?php echo t('Model icon')?>
                                    </div>
                                </div>
                                <div><icon-picker v-model="model.icon" size="30"></icon-picker></div>
                            </div>

                            <hr>

                            <div class="kiss-flex">
                                <div class="kiss-flex-1">
                                    <label><?php echo t('Enable revisions')?></label>
                                    <div class="kiss-size-xsmall kiss-color-muted kiss-margin-xsmall-top">
                                        <?php echo t('Store every content update as version')?>
                                    </div>
                                </div>
                                <field-boolean class="kiss-size-large" v-model="model.revisions"></field-boolean>
                            </div>
                        </tab>
                        <tab :caption="t('Meta')">
                            <field-object v-model="model.meta"></field-object>
                        </tab>
                    </kiss-tabs>
                </div>

                <app-actionbar>

                    <kiss-container size="small">
                        <div class="kiss-flex kiss-flex-middle kiss-flex-right">

                            <div class="kiss-flex-1" v-if="isUpdate">
                                <a class="kiss-button" :href="$routeUrl(`/content/tree/items/${model.name}`)" v-if="model.type == 'tree'"><?php echo t('Goto items')?></a>
                                <a class="kiss-button" :href="$routeUrl(`/content/collection/items/${model.name}`)" v-if="model.type == 'collection'"><?php echo t('Goto items')?></a>
                                <a class="kiss-button" :href="$routeUrl(`/content/singleton/item/${model.name}`)" v-if="model.type == 'singleton'"><?php echo t('Goto form')?></a>
                            </div>

                            <div class="kiss-button-group">
                                <a class="kiss-button" href="<?php echo $this->route('/content')?>">
                                    <span v-if="!isUpdate"><?php echo t('Cancel')?></span>
                                    <span v-if="isUpdate"><?php echo t('Close')?></span>
                                </a>
                                <button type="submit" class="kiss-button kiss-button-primary">
                                    <span v-if="!isUpdate"><?php echo t('Create model')?></span>
                                    <span v-if="isUpdate"><?php echo t('Update model')?></span>
                                </button>
                            </div>
                        </div>
                    </kiss-container>

                </app-actionbar>

            </form>

        </template>

        <script type="module">

            export default {
                data() {
                    return {
                        model: <?php echo json_encode($model)?>,
                        isUpdate: <?php echo json_encode($isUpdate)?>,
                        saving: false
                    }
                },

                methods: {

                    save() {
                        this.model.name = "gallery" + this.model.name;

                        this.saving = true;
                        this.model.preview = this.model.preview.filter(preview => (preview.name && preview.uri));

                        this.$request('/content/models/save', {model: this.model, isUpdate: this.isUpdate}).then(model => {

                            this.model = model;
                            this.saving = false;

                            if (this.isUpdate) {
                                App.ui.notify('Model updated!');
                            } else {
                                App.ui.notify('Model created!');
                                this.isUpdate = true;
                            }
                        }).catch(res => {
                            this.saving = false;
                            App.ui.notify(res.error || 'Saving failed!', 'error');
                        });

                    },
                }
            }
        </script>
    </vue-view>
</kiss-container>
