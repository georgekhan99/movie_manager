<template>
        <div
        class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Create Movie
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li class="font-medium">Distributor Manager</li>
                /
                <li class="font-medium text-primary">Create Movie</li>
            </ol>
        </nav>
    </div>
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">Create Movie</h3>
        </div>
        <form>
            <div class="p-6.5">
                <div class="mb-4.5">
                    <label class="mb-3 block text-sm font-bold text-black dark:text-white">
                        Movie Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your full name"
                        class="input-style"
                    />
                     <!-- <InputError v-if="$page.props.errors.Name" :message="$page.props.errors.Name" /> -->
                </div>

                <div class="mb-4.5">
                    <label
                        class="mb-3 block text-sm font-bold text-black dark:text-white"
                    >
                        Select Company
                    </label>
                    <select class="input-style" id="">
                        <option value="">Company 1</option>
                        <option value="">Company 2</option>
                        <option value="">Company 3</option>
                        <option value="">Company 4</option>
                    </select>
                    <!-- <InputError v-if="$page.props.errors.Surname" :message="$page.props.errors.Name" /> -->
                </div>
                <div class="mb-4.5">
                    <label
                        class="mb-3 block text-sm font-bold text-black dark:text-white"
                    >
                        Premiere Date
                    </label>
                    <input
                     
                        type="date"
                        placeholder="Enter your full name"
                        class="input-style"
                    />
                    <!-- <InputError v-if="$page.props.errors.Surname" :message="$page.props.errors.Name" /> -->
                </div>
                <button
                    type="submit"
                    class="flex w-40 p-3 justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>