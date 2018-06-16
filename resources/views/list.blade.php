<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>List of files</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                display: flex;
                justify-content: center;
                width: 100%;
            }
            .content {
                margin-top: 100px;
                width: 500px;
            }
            .list {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .item {
                border: solid 1px gray;
            }
            td {
                padding: 5px;
            }
            .add-url {
                display: flex;
                width: 100%;
                justify-content: space-between;
            }
            .input {
                width: 100%;
            }
            .btn {
                margin: 0 27px;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <form class="add-url" action="{{ URL::to('file/add') }}" method="post">
                {{ csrf_field() }}
                <input name="url" class="input" />
                <input type="submit" class="btn" value="{{ trans('file.add') }}" />
            </form>
            <table class="list">
                @foreach ($files as $file)
                    <tr class="item">
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->status_title }}</td>
                        <td>
                            @if ($file->status == \App\File::STATUS_COMPLETE)
                                <a href="{{ URL::to('file/download', ['id' => $file->id]) }}">{{ trans('file.download') }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </body>
</html>
