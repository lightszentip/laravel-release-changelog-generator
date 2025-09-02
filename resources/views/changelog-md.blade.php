# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
@isset($changelog)
@foreach ($changelog as $release => $changelogItems)
## [{{ ucfirst($release) }}]@if ($changelogItems['date'])
    - {{ \Carbon\Carbon::parse($changelogItems['date'])->format('Y-m-d') }}
@endif

{{-- Globale Item-Typen außer "modules" --}}
@foreach ($changelogItems as $changeType => $items)
@if ($changeType !== 'modules' && is_array($items))

### {{ ucfirst($changeType) }}

@foreach ($items as $item)
    - {{ $item['message'] }}
@endforeach
@endif
@endforeach

{{-- Module mit eigenen Item-Typen --}}
@if(isset($changelogItems['modules']) && is_array($changelogItems['modules']))
@foreach ($changelogItems['modules'] as $moduleName => $moduleData)

#### Modul: {{ $moduleName }}
@foreach ($moduleData as $moduleChangeType => $moduleItems)
@if (is_array($moduleItems))

##### {{ ucfirst($moduleChangeType) }}

@foreach ($moduleItems as $item)
      - {{ $item['message'] }}
@endforeach
@endif
@endforeach
@endforeach
@endif

@endforeach
@endisset
