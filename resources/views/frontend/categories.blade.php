<section>
    <div class="container">
        <h1 class="new_arrival_title"> CATEGORIES </h1>

        <div class="container">
            <div class="tabs-to-dropdown">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Dropdown for Mobile View -->
                    <select class="form-select d-md-none" id="categories-dropdown" onchange="filterCategories(this.value)">
                        @if ($subCategories != null)
                            @foreach ($subCategories as $subcategory)
                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                            @endforeach
                        @endif
                    </select>
                
                    <!-- Tabs for Desktop View -->
                    <ul class="nav nav-pills d-none d-md-flex text-center" id="pills-tab" role="tablist">
                        @if ($subCategories != null)
                            @foreach ($subCategories as $subcategory)
                                <li class="category_container" role="presentation"
                                    onclick="filterCategories('{{ $subcategory->id }}')">
                                    <a class="p-0" id="pills-{{ $subcategory->id }}-tab" data-toggle="pill"
                                        href="javascript:void(0)" role="tab"
                                        aria-controls="pills-{{ $subcategory->id }}" aria-selected="true">
                                    </a>
                                    <h6 class="m-0"> {{ $subcategory->name }} </h6>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                

                <div class="tab-content" id="pills-tabContent"></div>
            </div>
        </div>
    </div>

</section>
