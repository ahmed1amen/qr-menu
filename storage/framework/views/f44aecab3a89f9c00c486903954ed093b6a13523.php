<div class="card card-profile bg-secondary shadow">
    <div class="card-header">
        <h5 class="h3 mb-0"><?php echo e(__("Working Hours")); ?></h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?php echo e(route('restaurant.workinghours')); ?>" autocomplete="off" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="rid" name="rid" value="<?php echo e($restorant->id); ?>"/>
            <div class="form-group">
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <br/>
                <div class="row">
                    <div class="col-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="days" class="custom-control-input" id="<?php echo e('day'.$key); ?>" value=<?php echo e($key); ?>>
                            <label class="custom-control-label" for="<?php echo e('day'.$key); ?>"><?php echo e(__($value)); ?></label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                            </div>
                            <input id="<?php echo e($key.'_from'); ?>" name="<?php echo e($key.'_from'); ?>" class="flatpickr datetimepicker form-control" type="text" placeholder="<?php echo e(__('Time')); ?>">
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p class="display-4">-</p>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                            </div>
                            <input id="<?php echo e($key.'_to'); ?>" name="<?php echo e($key.'_to'); ?>" class="flatpickr datetimepicker form-control" type="text" placeholder="<?php echo e(__('Time')); ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH /home/ahmed/Downloads/Projects/qr-menu/resources/views/restorants/partials/hours.blade.php ENDPATH**/ ?>