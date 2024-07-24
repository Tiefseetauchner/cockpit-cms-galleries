<kiss-container class="kiss-margin-small">

    <ul class="kiss-breadcrumbs">
        <li><a href="<?php echo $this->route('/gallery') ?>"><?php echo t('Gallery') ?></a></li>
    </ul>

    <vue-view>
        <template>

            <app-loader class="kiss-margin-large" v-if="loading"></app-loader>

            <div class="animated fadeIn kiss-height-50vh kiss-flex kiss-flex-middle kiss-flex-center kiss-align-center kiss-color-muted kiss-margin-large" v-if="!loading && !galleries.length">
                <div>
                    <kiss-svg src="<?php echo $this->base('gallery:icon.svg') ?>" width="40" height="40"></kiss-svg>
                    <p class="kiss-size-large kiss-margin-top"><?php echo t('No galleries') ?></p>
                </div>
            </div>

            <div class="kiss-margin-large" v-if="!loading && galleries.length">

                <div class="kiss-margin">
                    <input type="text" class="kiss-input" :placeholder="t('Filter galleries...')" v-model="filter">
                </div>

                <div class="animated fadeIn kiss-height-50vh kiss-flex kiss-flex-middle kiss-flex-center kiss-align-center kiss-color-muted kiss-margin-large" v-if="!singletons.length && !lists.length">
                    <div>
                        <kiss-svg src="<?php echo $this->base('gallery:icon.svg') ?>" width="40" height="40"></kiss-svg>
                        <p class="kiss-size-large kiss-margin-top"><?php echo t('No galleries found') ?></p>
                    </div>
                </div>

                <div class="kiss-margin" v-if="lists.length">

                    <div class="kiss-margin kiss-text-caption kiss-text-bold kiss-color-muted kiss-size-small"><?php echo t('Galleries') ?></div>

                    <kiss-card class="kiss-margin-small kiss-flex animated fadeIn" theme="shadowed contrast" hover="shadow" v-for="gallery in galleries">
                        <div class="kiss-position-relative kiss-padding-small kiss-bgcolor-contrast">
                            <canvas width="40" height="40"></canvas>
                            <div class="kiss-cover kiss-flex kiss-flex-middle kiss-flex-center">
                                <div :style="{color: gallery.color || 'inherit' }"><kiss-svg :src="$baseUrl(gallery.icon || 'gallery:assets/icons/'+gallery.type+'.svg')" width="30" height="30"></kiss-svg></div>
                            </div>
                            <a class="kiss-cover" :href="$routeUrl(`/gallery/${gallery.type}/items/${gallery.name}`)" :aria-label="gallery.label || gallery.name"></a>
                        </div>
                        <div class="kiss-padding-small kiss-flex-1 kiss-position-relative">
                            <div class="kiss-size-small kiss-text-bold kiss-text-truncate">{{ gallery.label || gallery.name }}</div>
                            <div class="kiss-margin-xsmall-top kiss-color-muted kiss-size-xsmall kiss-text-truncate">{{gallery.info || gallery.type}}</div>
                            <a class="kiss-cover" :href="$routeUrl(`/gallery/${gallery.type}/items/${gallery.name}`)" :aria-label="gallery.label || gallery.name"></a>
                        </div>
                        <a class="kiss-padding-small" @click="toggleGalleryActions(gallery)">
                            <icon>more_horiz</icon>
                        </a>
                    </kiss-card>

                </div>

            </div>

            <app-actionbar>

                <kiss-container>
                    <div class="kiss-flex kiss-flex-middle">
                        <div class="kiss-flex-1"></div>
                        <?php if ($this->helper('acl')->isAllowed("gallery/manage")) : ?>
                            <a class="kiss-button kiss-button-primary" href="<?php echo $this->route('/gallery/create') ?>"><?php echo t('Create gallery') ?></a>
                        <?php endif ?>
                    </div>
                </kiss-container>

            </app-actionbar>

            <kiss-popout :open="actionModel && 'true'" @popoutclose="toggleModelActions(null)">
                <kiss-gallery>
                    <kiss-navlist v-if="actionModel">
                        <ul>
                            <li class="kiss-nav-header">{{ actionModel.label || actionModel.name }}</li>
                            <li v-if="actionModel.type=='collection'">
                                <a class="kiss-flex kiss-flex-middle" :href="$routeUrl(`/gallery/collection/item/${actionModel.name}`)">
                                    <icon class="kiss-margin-small-right">add_circle</icon>
                                    <?php echo t('Create item') ?>
                                </a>
                            </li>
                            <li class="kiss-nav-divider"></li>
                            <li>
                                <a class="kiss-color-danger kiss-flex kiss-flex-middle" @click="remove(actionModel)">
                                    <icon class="kiss-margin-small-right">delete</icon>
                                    <?php echo t('Delete') ?>
                                </a>
                            </li>
                        </ul>
                    </kiss-navlist>
                </kiss-gallery>
            </kiss-popout>

        </template>

        <script type="module">
            export default {
              data() {
                return {
                  galleries: [],
                  loading: false,
                  actionModel: null,
                  group: null,
                  filter: '',
                  filterModelType: null
                }
              },

              computed: {
                galleries() {
                    return this.galleries
                      .filter(model => {
                          if (this.filter && !`${model.name} ${model.label}`.toLocaleLowerCase().includes(this.filter.toLocaleLowerCase())) {
                              return false;
                          }

                          return ['collection', 'tree'].includes(model.type) && (!this.group || this.group == model.group);
                      });
                },

              }

              mounted() {
                this.load();
              },

              methods: {

                load() {

                    this.loading = true;

                    App.utils.getContentModels().then(galleries => {
                        this.galleries = Object.values(galleries)
                          .filter(model => {
                            if (this.filter && !`${model.name} ${model.label}`.toLocaleLowerCase().includes('gallery_'.toLocaleLowerCase())) {
                                  return false;
                              }
                          });
                        this.loading = false;
                    })
                },

                toggleModelActions(model) {
                    if (!model) {
                        setTimeout(() => this.actionModel = null, 300);
                        return;
                    }

                    this.actionModel = model;
                },

                remove(model) {
                  App.ui.confirm('Are you sure?', () => {

                      this.$request(`/content/models/remove/${model.name}`, {}).then(res => {
                          this.models.splice(this.models.indexOf(model), 1);
                          App.ui.notify('Model removed!');
                      }).catch(rsp => {
                          App.ui.notify(rsp.error || 'Removing model failed!', 'error');
                      });;
                  });
                },
              }
            }
        </script>

    </vue-view>


</kiss-container>


<?php $this->start('app-side-panel') ?>

<h2 class="kiss-size-4"><?php echo t('Gallery') ?></h2>

<kiss-navlist>
    <ul>
        <li>
            <a class="kiss-link-muted kiss-flex kiss-flex-middle kiss-text-bold" href="<?php echo $this->route('/gallery') ?>">
                <kiss-svg class="kiss-margin-small-right" src="<?php echo $this->base('gallery:icon.svg') ?>" width="20" height="20"><canvas width="20" height="20"></canvas></kiss-svg>
                <?php echo t('Overview') ?>
            </a>
        </li>
    </ul>
</kiss-navlist>

<div class="kiss-margin" id="galleries-aside"></div>

<?php if ($this->helper('acl')->isAllowed("content/:galleries/manage")) : ?>
    <kiss-navlist>
        <ul>
            <li class="kiss-nav-header kiss-margin-top kiss-margin-xsmall-bottom"><?php echo t('Create') ?></li>
            <li>
                <a class="kiss-color-muted kiss-flex kiss-flex-middle" href="<?php echo $this->route('/gallery/create') ?>">
                    <kiss-svg class="kiss-margin-small-right" src="<?php echo $this->base('gallery:assets/icons/create.svg') ?>" width="20" height="20"><canvas width="20" height="20"></canvas></kiss-svg>
                    <?php echo t('Create Gallery') ?>
                </a>
            </li>
        </ul>
    </kiss-navlist>
<?php endif ?>


<?php $this->end('app-side-panel') ?>
