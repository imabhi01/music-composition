<div class="form-group">
    <label for="signs">Zoodiac Signs</label>
    <select name="zoodiac_sign" id="signs" class="form-control" required>
        <option value="{{ old('zoodiac_sign') }}" selected>Select Your Zoodiac Sign</option>
        @foreach($signs as $key => $sign)
            <option value="{{ $sign }}" @if(old('zoodiac_sign') == $sign) selected @endif {{ isset($audio) ? ($audio->zoodiac_sign == $sign ? 'selected' : '') : '' }}>{{ $sign }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="category">Category</label>
    <select name="category" id="category" class="form-control" required>
        <option value="" selected>Select the category</option>
        @foreach($category as $key => $cat)
            <option value="{{ $cat }}" @if(old('category') == $cat) selected @endif {{ isset($audio) ? ($audio->category == $cat ? 'selected' : '') : '' }}>{{ $cat }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="audio_file">Select Audio File</label>
    <input type="file" name="audio_file" id="audio_file" class="form-control" placeholder="Choose an audio file" required/>
    
    @if(isset($audio))
        <div class="row">
            <strong>Previous Audio Preview</strong>
            <div class="col">
                <audio controls>
                    <source src="{{ asset('storage') . '/'. $audio->upload->file_path }}" type="audio/ogg">
                    <source src="{{ asset('storage') . '/'. $audio->upload->file_path }}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>    
    @endif
</div>

<div class="form-group mt-2">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>