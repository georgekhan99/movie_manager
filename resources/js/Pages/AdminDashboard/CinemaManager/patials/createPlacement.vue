<template>

<div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                  <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">
                      Create Placement 
                    </h3>
                  </div>
                  <form action="#">
                    <div class="p-6.5">
                      <div class="mb-5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                          <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Place Name
                          </label>
                          <input type="text" placeholder="Enter your first name" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        <div data-lastpass-icon-root="" style="position: relative !important; height: 0px !important; width: 0px !important; float: left !important;"></div></div>

                        <div class="w-full xl:w-1/2">
                          <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                           Canvas Size
                          </label>
                          <input type="text" placeholder="Enter your last name" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                      </div>

                      <div class="mb-5.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                          <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Select Cinema
                          </label>
                          <select name="" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" id="">
                            <option value="" v-for="i in 10">
                              {{ i }}
                            </option>
                          </select>
                        </div>
                        <div class="w-full xl:w-1/2">
                          <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                           Canvas Size
                          </label>
                          <input type="text" placeholder="Enter your last name" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary">
                        </div>
                      </div>
                      <button class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                        Send Message
                      </button>
                    </div>
                  </form>
                </div>

</template>